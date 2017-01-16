<?php

namespace Infinety\ConfigManager;

use Illuminate\Support\Facades\Facade;

class ConfigManagerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'config.helper';
    }
}
