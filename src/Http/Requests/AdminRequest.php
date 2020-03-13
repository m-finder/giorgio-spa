<?php

namespace GiorgioSpa\Http\Requests;

use App\Http\Requests\Traits\RequestErrorMessage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
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
            case 'POST':
                return [
                    'name' => 'required|max:255|unique:admins',
                    'email' => 'required|email|unique:admins',
                    'password' => 'required|min:6|max:32',
                    'role_id' => [
                        'required',
                        'integer',
                        Rule::exists('roles', 'id'),
                    ],
                ];
            case 'PUT':
                $id = $this->route('id');
                return [
                    'name' => [
                        'required',
                        'max:255',
                        Rule::unique('admins')->ignore($id)
                    ],
                    'email' => [
                        'required',
                        'email',
                        Rule::unique('admins')->ignore($id)
                    ],
                    'role_id' => [
                        'required',
                        'integer',
                        Rule::exists('roles', 'id'),
                    ],
                    'password' => 'nullable|min:6|max:32',

                ];
            default:
                break;
        }
    }

    public function attributes()
    {
        return [
            'name' => '用户名称',
            'email' => '登录邮箱',
            'password' => '登录密码',
            'role_id' => '所属角色',
        ];
    }

    public function message()
    {
        return '无效的数据。';
    }

}
