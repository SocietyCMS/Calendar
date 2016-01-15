<?php

namespace Modules\Calendar\Installer;

class RegisterDefaultPermissions
{

    public $defaultPermissions = [

        'manage-calendar' => [
            'display_name' => 'calendar::module-permissions.manage-calendar.display_name',
            'description' => 'calendar::module-permissions.manage-calendar.description'
        ],

    ];
}