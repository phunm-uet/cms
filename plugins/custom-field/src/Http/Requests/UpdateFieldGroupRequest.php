<?php

namespace Botble\CustomField\Http\Requests;

use Botble\Base\Http\Requests\Request;

class UpdateFieldGroupRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @author Sang Nguyen
     */
    public function authorize()
    {

        // Determine if the user is authorized to view the module.
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
            'order' => 'integer|min:0',
            'rules' => 'json|required',
            'title' => 'string|required|max:255',
            'status' => 'required|in:0,1',
        ];
    }
}
