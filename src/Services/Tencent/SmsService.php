<?php

namespace App\Services\Tencent;


use Exception;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;
use Qcloud\Sms\SmsSingleSender;

class SmsService
{
    // todo code 替换成自己的
    //初始密码(您的后台登录密码为{1}，为了您的使用安全，请在登录平台后尽快修改密码。)
    const INIT_PASSWORD = '111';

    //验证码(验证码为{1}，{1}分钟内有效，如非本人操作请忽略本短信；为了保障您的账户安全，请勿向他人泄露验证码信息。)
    const VERIFICATION_CODE = '222';

    // todo 报警短信模版
    const WARNING_CODE = '333';

    const CODE_SUFFIX = '_code';

    /**
     * @param $phone
     * @param int $expired
     * @param string $suffix
     */
    public static function sendSmsCode($phone, int $expired = 5, string $suffix = self::CODE_SUFFIX)
    {
        $bool = self::canSendCode($phone);
        if (!$bool) {
            abort(403, '短信发送频繁,请稍后重试');
        }
        $randomCode = config('app.env') == 'local' ? '1234' : random_int(1000, 9999);
        $template = self::VERIFICATION_CODE;
        $content = [
            $randomCode,
            $expired
        ];
        self::setCacheCode($phone, $randomCode, $expired, $suffix);
        try {
            self::send($phone, $template, $content);
        } catch (Exception $e) {
            abort(403, '短信发送失败，请稍后重试');
        }
    }

    /**
     * @param $phone
     * @param $smsCode
     * @param string $suffix
     * @return bool
     */
    public static function validateSmsCode($phone, $smsCode, string $suffix = self::CODE_SUFFIX): bool
    {
        $code = config('app.env') == 'local' ? '1234' : Cache::get($phone . $suffix);
        if ($smsCode == $code) {
            try {
                Cache::delete($phone . $suffix);
            } catch (InvalidArgumentException $e) {
                info('短信验证码删除失败', [
                    'phone' => $phone,
                    'smsCode' => $smsCode,
                    'msg' => $e->getMessage()
                ]);
            }
            return true;
        }
        return false;
    }

    /**
     * @param $phone
     * @param string $password
     * @throws Exception
     */
    public static function sendInitPassword($phone, string $password = 'abc123')
    {
        $template = self::INIT_PASSWORD;
        $content = [
            $password
        ];
        self::send($phone, $template, $content);
    }

    /**
     * @throws Exception
     */
    protected static function send(string $phone, string $template, array $content)
    {
        if (config('app.env') == 'local') {
            info('测试环境短信模拟发送', ['phone' => $phone, 'template' => $template, 'content' => $content]);
            return;
        }
        try {
            // 短信应用SDK AppID
            // 1400开头
            $appId = config('tencent_cloud.sdk_app_id');
            // 短信应用SDK AppKey
            $appKey = config('tencent_cloud.app_key');
            // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`
            $smsSign = config('tencent_cloud.sign_name');
            $sender = new SmsSingleSender($appId, $appKey);
            info('腾讯短信', ['phone' => $phone, 'content' => $content]);
            // 签名参数不能为空串
            $sender->sendWithParam("86", $phone, $template, $content, $smsSign, "", "");
        } catch (Exception $e) {
            error_log('腾讯短信', $e->getMessage());
            throw new $e;
        }
    }

    protected static function setCacheCode($phone, $code, $expired, $suffix = self::CODE_SUFFIX)
    {
        if (is_null(Cache::get($phone))) {
            Cache::put($phone, 1, now()->addMinute());
        } else {
            Cache::increment($phone, 1);
        }

        Cache::put($phone . $suffix, $code, now()->addMinutes($expired));
    }

    protected static function canSendCode($phone): bool
    {
        $can = Cache::get($phone) ?? 0;
        return $can < 3;
    }

    /**
     * @throws Exception
     */
    public static function sendWarning($phone)
    {
        $template = self::WARNING_CODE;
        $content = [];
        self::send($phone, $template, $content);
    }
}
