<?php

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

if (!function_exists('admin')) {
    /**
     * 当前管理员
     * @return Authenticatable|null|Admin
     */
    function admin(): Authenticatable|null|Admin
    {
        return Auth::user();
    }
}


if (!function_exists('filter_special')) {
    /**
     * 过滤字符串中的特殊字符
     */
    function filter_special($str): array|string
    {
        if (is_null($str)) {
            return '';
        }
        $search = [
            ' ',
            '　',
            '‭',
            '‬',
            chr(194) . chr(160),
            "\n",
            "\r",
            "\t",
            "\r\n",
            "\f",
            "\v",
        ];
        return str_replace($search, '', $str);
    }
}


if (!function_exists('yuan2fen')) {
    /**
     * 金额元转分
     * @param $amount
     * @param int $scale
     * @return string
     */
    function yuan2fen($amount, int $scale = 0): string
    {
        return bcmul($amount, 100, $scale);
    }
}


if (!function_exists('fen2yuan')) {
    /**
     * 金额分转元
     * @param int|null $amount
     * @param int $scale
     * @param bool $format
     * @return int|string
     */
    function fen2yuan(int $amount = null, int $scale = 2, bool $format = false): int|string
    {
        return empty($amount) ? 0 : ($format ? number_format(bcdiv($amount, 100, 5), $scale) : bcdiv($amount, 100, 2));
    }
}


if (!function_exists('array2string')) {
    /**
     * 数组转字符串
     * @param $arr
     * @return mixed
     */
    function array2string($arr): mixed
    {
        if (is_array($arr)) {
            return implode(',', array_map('array2string', $arr));
        }
        return $arr;
    }
}

if (!function_exists('mask_name')) {
    /**
     * 姓名脱敏,首位最后一位*号
     * @param $name
     * @return string
     * @noinspection PhpPureAttributeCanBeAddedInspection
     */
    function mask_name($name): string
    {
        $length = Str::length($name);
        $first = Str::substr($name, 0, 1) . '*';
        return $length <= 2 ? $first : $first . Str::substr($name, -1);
    }
}

if (!function_exists('mask_cert_no')) {
    /**
     * 身份证号脱敏,保留前四后四
     * @param $certNo
     * @return string
     * @noinspection PhpPureAttributeCanBeAddedInspection
     */
    function mask_cert_no($certNo): string
    {
        return Str::substr($certNo, 0, 4) . '**********' . Str::substr($certNo, -4);
    }
}

if (!function_exists('mask_bank_card_no')) {
    /**
     * 银行卡号脱敏,保留前四后四
     * @param $bankCardNo
     * @return string
     * @noinspection PhpPureAttributeCanBeAddedInspection
     */
    function mask_bank_card_no($bankCardNo): string
    {
        return Str::substr($bankCardNo, 0, 4) . '**********' . Str::substr($bankCardNo, -4);
    }
}

if (!function_exists('mask_phone')) {
    /**
     * 手机号号脱敏,保留前三后四
     * @param $phone
     * @return string
     * @noinspection PhpPureAttributeCanBeAddedInspection
     */
    function mask_phone($phone): string
    {
        return Str::substr($phone, 0, 3) . '*****' . Str::substr($phone, -3);
    }
}


if (!function_exists('add_timestamps')) {
    /**
     * 给数组添加创建时间,更新时间
     */
    function add_timestamps(&$array)
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        if (is_array(head($array))) {
            data_fill($array, '*.created_at', $now);
            data_fill($array, '*.updated_at', $now);
        } else {
            data_fill($array, 'created_at', $now);
            data_fill($array, 'updated_at', $now);
        }
    }
}


if (!function_exists('split_native_place')) {
    /**
     * 从地址中分割籍贯地址
     */
    function split_native_place($str): string
    {
        $signs = ['市', '区', '县', '旗'];
        $finalPos = false;
        foreach ($signs as $sign) {
            $pos = mb_strrpos($str, $sign);
            if ($finalPos === false || $pos > $finalPos) {
                $finalPos = $pos;
            }
        }
        return mb_substr($str, 0, $finalPos + 1);
    }
}


if (!function_exists('binging_into_sql')) {
    /**
     * 将binging参数添加到sql的中
     */
    function binging_into_sql($bindings, $sql)
    {
        return array_reduce($bindings, function ($sql, $binding) {
            return preg_replace('/\?/', is_numeric($binding) ? $binding : "'" . $binding . "'", $sql, 1);
        }, $sql);
    }
}


if (!function_exists('url_basename')) {
    /**
     * 从文件地址中截取文件名称
     * @param string $url
     * @return string
     * @noinspection PhpPureAttributeCanBeAddedInspection
     */
    function url_basename(string $url): string
    {
        return Str::before(Str::afterLast($url, '/'), '?');
    }
}


if (!function_exists('array_key_sort')) {

    /**
     * 多维数组排序
     * @param array $params
     * @return array
     */
    function array_key_sort(array $params): array
    {
        // 数组升序排序
        ksort($params);

        // 如果有下一级 对下一级使用排序 返回
        foreach ($params as $k => $v) {
            if (is_array($v)) {
                $v = array_key_sort($v);
            }
            $params[$k] = $v;
        }

        return $params;
    }
}

if (!function_exists('route_display')) {
    /**
     * 路由名称转中文
     * @param string $route
     * @return string
     */
    function route_display(string $route = ''): string
    {
        if (empty($route)) {
            $route = Route::currentRouteName() ?? '';
        }
        $arr = explode('.', $route);
        $group = config('permission.groups')[$arr[0]] ?? '';
        $method = isset($arr[1]) ? config('permission.methods')[$arr[1]] : '';
        return $group . $method;
    }
}

if (!function_exists('route_group')) {
    /**
     * 路由名称转中文
     * @param string $route
     * @return string
     */
    function route_group(string $route = ''): string
    {
        if (empty($route)) {
            $route = Route::currentRouteName() ?? '';
        }
        $arr = explode('.', $route);
        return $arr[0] ?? '';
    }
}

//删除文件 $path为绝对路径
if (!function_exists('del_file')) {

    function del_file($path)
    {
        $url = iconv('utf-8', 'gbk', $path);
        if (PATH_SEPARATOR == ':') {
            // linux
            unlink($path);
        } else {
            // windows
            unlink($url);
        }
    }

}


if (!function_exists('validate_date')) {
    /**
     * 日期参数校验
     * @throws Exception
     */
    function validate_date($date, $format = 'Y-m-d')
    {
        $validator = Validator::make([
            'date' => $date
        ], [
            'date' => 'date_format:' . $format,
        ], [
            'date.date_format' => '日期格式错误,请使用' . $format . '格式',
        ]);
        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }
    }
}


if (!function_exists('unsigned_uuid')) {

    /**
     * 无中划线的uuid
     * @return string
     */
    function unsigned_uuid(): string
    {
        return (string) Str::of(Str::uuid())->replace('-', '');
    }
}


