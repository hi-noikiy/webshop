<?php

namespace WTG\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Create address request.
 *
 * @package     WTG\Http
 * @subpackage  Requests
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CreateAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => ['required'],
            'address'   => ['required'],
            'postcode'  => ['required'],
            'city'      => ['required'],
            'phone'     => [],
            'mobile'    => []
        ];
    }
}
