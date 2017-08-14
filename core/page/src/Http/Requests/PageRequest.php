<?php

namespace Botble\Page\Http\Requests;

use Botble\Base\Http\Requests\Request;

class PageRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @author Sang Nguyen
     */
    public function authorize()
    {
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
            'name' => 'required|max:120',
            'content' => 'required',
            'slug' => 'required',
        ];
    }
}
