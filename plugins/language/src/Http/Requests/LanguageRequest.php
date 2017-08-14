<?php

namespace Botble\Language\Http\Requests;

use Botble\Base\Http\Requests\Request;

class LanguageRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @author Sang Nguyen
     */
    public function authorize()
    {

        // Determine if the user is authorized to view the language.
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
        return [
            'name' => 'required|max:30|min:2',
            'code' => 'required|max:10|min:2',
            'locale' => 'required|max:10|min:2',
            'flag' => 'required',
            'is_rtl' => 'required',
            'order' => 'required|numeric',
        ];
    }
}
