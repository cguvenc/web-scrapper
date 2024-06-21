<?php

namespace App\Http\Requests\back;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWebsiteRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
           'update_store_url' => 'required|url|max:255',
           'update_consumer_key' => 'required|max:255',
           'update_consumer_secret' => 'required|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'update_store_url.required' => 'store_url boş bırakılamaz.',
            'update_store_url.url' => 'Lütfen geçerli bir url giriniz.',
            'update_store_url.max' => 'store_url en fazla 255 karakter olmalıdır.',
            'update_consumer_key.required' => 'consumer_key boş bırakılamaz.',
            'update_consumer_key.max' => 'consumer_key en fazla 255 karakter olmalıdır.',
            'update_consumer_secret.required' => 'consumer_secret boş bırakılamaz.',
            'update_consumer_secret.max' => 'consumer_secret en fazla 255 karakter olmalıdır.'
        ];
    }
}
