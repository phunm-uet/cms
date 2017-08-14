<?php

namespace Botble\Translation\Http\Requests;

use Botble\Base\Http\Requests\Request;

class TranslationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @author Sang Nguyen
     */
    public function authorize()
    {

        // Determine if the user is authorized to view the translation.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Sang Nguyen
     */
    public function rules()
    {
        return ['name' => 'required'];
    }
}
