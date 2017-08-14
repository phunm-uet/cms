<?php

namespace Botble\MenuLeftHand\Observers;

use Botble\MenuLeftHand\Models\MenuLeftHand;

class MenuLeftHandObserver
{
    public function saved()
    {
        MenuLeftHand::buildMenu();
    }
}
