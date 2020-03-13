<?php

namespace GiorgioSpa\Http\Requests;

use App\Http\Requests\Traits\RequestErrorMessage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ElementRequest extends FormRequest
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
                    'name' => 'required|max:255|unique:elements',
                    'code' => 'required|max:255|unique:elements',
                    'path' => 'required|max:255',
                    'method' => 'required|max:255',
                ];
            case 'PUT':
                $id = $this->route('id');
                return [
                    'name' => [
                        'required',
                        'max:255',
                        Rule::unique('elements')->ignore($id)
                    ],
                    'code' => [
                        'required',
                        'max:255',
                        Rule::unique('elements')->ignore($id)
                    ],
                    'path' => 'required|max:255',
                    'method' => 'required|max:255',
                ];
            default:
                break;
        }
    }

    public function attributes()
    {
        return [
            'name' => '资源名称',
            'code' => '资源编号',
            'method' => '请求方法',
            'path' => '请求路径',
        ];
    }

    public function message()
    {
        return '无效的数据。';
    }
}
