<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'email' => 'required|string|email|unique:users|max:191',
            'name' => 'required|string|max:191',
            'user_catalogue_id' => 'required',
            'password' => 'required|string|min:6',
            're_password' => 'required|same:password',
            'avatar' => 'mimes:jpeg,jpg,png,gif|max:2048',
            'phone' => 'required',
            'birthday' => 'required',
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
            'password.required' => 'Bạn chưa nhập mật khẩu.',
            'password.min' => 'Độ dài mật lại tối đa là 6 ký tự.',
            're_password.required' => 'Bạn chưa nhập lại mật khẩu.',
            're_password.same' => 'Mật khẩu nhập lại chưa chính xác.',
            'phone.required' => 'Bạn chưa nhập số điện thoại',
            'birthday.required' => 'Bạn phải nhập ngày tháng năm sinh',
        ];
    }
}
