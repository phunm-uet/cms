<?php

namespace Botble\Setting\Http\Requests;

use Botble\Base\Http\Requests\Request;

class SettingRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @author Sang Nguyen
     */
    public function authorize()
    {

        // Determine if the user is authorized to view the setting.
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
        return [];
    }
}
