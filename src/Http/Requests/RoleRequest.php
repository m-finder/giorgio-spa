<?php

namespace GiorgioSpa\Http\Requests;

use App\Http\Requests\Traits\RequestErrorMessage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
                    'name'  => 'required|max:255|unique:roles',
                    'alias' => 'required|max:255|unique:roles'
                ];
            case 'PUT':
                $id = $this->route('id');
                return [
                    'name' => [
                        'required',
                        'max:255',
                        Rule::unique('roles')->ignore($id)
                    ],
                    'alias' => [
                        'required',
                        'max:255',
                        Rule::unique('roles')->ignore($id)
                    ],
                ];
            default: break;
        }
    }

    public function attributes()
    {
        return [
            'name' => '角色名称',
            'alias' => '角色别名',
        ];
    }

    public function message()
    {
        return '无效的数据。';
    }
}
