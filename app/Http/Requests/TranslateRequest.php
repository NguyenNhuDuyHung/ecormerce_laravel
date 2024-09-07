<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
class TranslateRequest extends FormRequest
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
            'translate_name' => 'required',
            'translate_canonical' => 'required|unique:routers,canonical, ' . $this->id . ',module_id',
            // 'translate_canonical' => [
            //     'required',
            //     function ($attribute, $value, $fail) {
            //         $option = $this->input("option");

            //         $exist = DB::table('routers')
            //             ->where('canonical', $value)
            //             ->where('language_id', '<>', $option['languageId'])
            //             ->where('id', '<>', $option['id'])
            //             ->exists();

            //         if ($exist) {
            //             $fail('Đường dẫn đã tồn tại');
            //         }
            //     }
            // ]
        ];
    }
    public function messages(): array
    {
        return [
            'translate_name.required' => 'Tiêu đề không được để trống',
            'translate_canonical.required' => 'Đường dẫn không được để trống',
            'translate_canonical.unique' => 'Đường dẫn đã tồn tại',
        ];
    }


}
