<?php

namespace Botble\ACL\Http\Requests;

use Botble\Base\Http\Requests\Request;
use Sentinel;

class UpdatePasswordRequest extends Request
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
        if (Sentinel::getUser()->isSuperUser()) {
            return [
                'password' => 'required|min:6|max:60',
                'password_confirmation' => 'same:password',
            ];
        } else {
            return [
                'old_password' => 'required|min:6|max:60',
                'password' => 'required|min:6|max:60',
                'password_confirmation' => 'same:password',
            ];
        }
    }
}
