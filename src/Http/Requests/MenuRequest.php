<?php

namespace GiorgioSpa\Http\Requests;

use GiorgioSpa\Http\Requests\Traits\RequestErrorMessage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MenuRequest extends FormRequest
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
                    'name' => 'required|max:255|unique:menus',
                    'title' => 'required|max:255|unique:menus',
                    'path' => 'required|max:255',
                    'component' => 'required|max:255',
                    'parent_id' => [
                        'required',
                        'integer',
                    ],
                ];
            case 'PUT':
                $id = $this->route('id');
                return [
                    'name' => [
                        'required',
                        'max:255'
                    ],
                    'title' => [
                        'required',
                        'max:255',
                        Rule::unique('menus')->ignore($id)
                    ],
                    'parent_id' => [
                        'required',
                        'integer',
                    ],
                    'path' => 'required|max:255',
                    'component' => 'required|max:255',
                ];
            default:
                break;
        }
    }

    public function attributes()
    {
        return [
            'parent_id' => '上级菜单',
            'name' => '视图名称',
            'title' => '菜单名称',
            'component' => '视图路径',
            'path' => '跳转地址',
        ];
    }

    public function message()
    {
        return '无效的数据。';
    }
}
