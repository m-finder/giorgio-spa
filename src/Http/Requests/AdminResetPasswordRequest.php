<?php

namespace GiorgioSpa\Http\Requests;

use GiorgioSpa\Http\Requests\Traits\RequestErrorMessage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminResetPasswordRequest extends FormRequest
{
    use RequestErrorMessage;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'PUT':
                return [
                    'original_password' => 'required|min:6|max:32',
                    'password' => 'nullable|min:6|max:32',
                ];
            default:
                break;
        }
    }

    public function attributes()
    {
        return [
            'original_password' => '原始密码',
            'password' => '新设密码',
        ];
    }

    public function message()
    {
        return '无效的数据。';
    }
}
