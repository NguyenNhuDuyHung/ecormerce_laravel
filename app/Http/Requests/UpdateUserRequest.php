<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|unique:users,email, '.$this->id.'|max:191',
            'name' => 'required|string|max:191',
            'user_catalogue_id' => 'required',
            'avatar' => 'mimes:jpeg,jpg,png,gif|max:2048',
            'phone' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Bạn chưa nhập email.',
            'email.string' => 'Email phải là chuỗi ký tự.',
            'email.unique' => 'Email đã tồn tại.',
            'email.email' => 'Email chưa đúng định dạng. Ví dụ: abc@gmail.com',
            'email.max' => 'Độ dài email tối đa là 191 ky tự.',
            'user_catalogue_id.required' => 'Bạn phải chọn nhóm thành viên.',
            'user_catologue_id.gt' => 'Bạn chưa chọn nhóm thành viên.',
            'name.required' => 'Bạn chưa nhập họ tên.',
            'name.string' => 'Họ tên phải là chuỗi ký tự.',
            'name.max' => 'Độ dài họ tên tối đa là 191 ký tự.',
            'phone.required' => 'Bạn chưa nhập số điện thoại',
        ];
    }
}
