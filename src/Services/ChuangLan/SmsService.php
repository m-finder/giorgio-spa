<?php
namespace GiorgioSpa\Services\ChuangLan;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SmsService
{
    const SIGN_NAME = '【清宁云服】';
    const INIT_PASSWORD = '您的后台登录密码为{s}，为了您的使用安全，请在登录平台后尽快修改密码。';
    const VERIFICATION_CODE = '验证码为{s}，{s}分钟内有效，如非本人操作请忽略本短信；为了保障您的账户安全，请勿向他人泄露验证码信息。';

    const  CODE_SUFFIX = '_code';

    /**
     * @param $phone
     * @param int $expired
     * @param string $suffix
     * @throws \Exception
     */
    public static function sendSmsCode($phone, int $expired = 5, string $suffix = self::CODE_SUFFIX)
    {
        $template = self::VERIFICATION_CODE;
        $bool = self::canSendCode($phone);
        if (!$bool) {
            abort(429,'短信发送频繁,请稍后重试');
        }
        $randomCode = config('app.env') == 'local' ? '1234' : random_int(1000, 9999);
        $template = self::SIGN_NAME . Str::replaceArray('{s}', [$randomCode, $expired], $template);
        self::setCacheCode($phone, $randomCode, $expired, $suffix);
        self::send($phone, $template);
    }

    /**
     * @param $phone
     * @param $smsCode
     * @param string $suffix
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function validateSmsCode($phone, $smsCode, string $suffix = self::CODE_SUFFIX): bool
    {
        $code = config('app.env') == 'local' ? '1234' : Cache::get($phone . $suffix);
        if ($smsCode == $code) {
            Cache::delete($phone . $suffix);
            return true;
        }
        return false;
    }

    /**
     * @param $phone
     * @param string $password
     * @throws \Exception
     */
    public static function sendInitPassword($phone, string $password = 'abc123')
    {
        $template = self::INIT_PASSWORD;
        $template = self::SIGN_NAME . Str::replaceArray('{s}', [$password], $template);
        self::send($phone, $template);
    }

    protected static function setCacheCode($key, $code, $expired, $suffix = self::CODE_SUFFIX)
    {
        if (is_null(Cache::get($key))) {
            Cache::put($key, 1, now()->addMinute());
        } else {
            Cache::increment($key, 1);
        }

        Cache::put($key . $suffix, $code, now()->addMinutes($expired));
    }

    protected static function canSendCode($phone): bool
    {
        $can = Cache::get($phone) ?? 0;
        return $can < 3;
    }

    /**
     * @throws \Exception
     */
    public static function send(string $phone, string $template, string $method = 'post')
    {
        if (config('app.env') == 'local') {
            info('测试环境短信模拟发送', ['phone' => $phone, 'template' => $template]);
            return;
        }
        $account = config('chuang_lan.account'); // API账号
        $password = config('chuang_lan.password'); // API密码
        info('创蓝短信请求', ['phone' => $phone, 'msg' => $template]);
        $res = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post(config('chuang_lan.uri'), [
            "account" => $account,
            "password" => $password,
            "msg" => $template,
            "phone" => $phone,
        ]);
        $result = $res->body();
        $result = json_decode($result, true);
        abort(400, $result['errorMsg']);
        info('创蓝短信返回', ['phone' => $phone, 'msg' => $template, 'status' => $res->status(), 'response' => $result]);
    }

}
