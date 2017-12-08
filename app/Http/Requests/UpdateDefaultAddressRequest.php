<?php

namespace WTG\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDefaultAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user() === null) {
            return false;
        }

        return $this
            ->user()
            ->company
            ->addresses()
            ->where('id', $this->input('address-id'))
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address-id' => ['required', 'exists:addresses,id']
        ];
    }
}
