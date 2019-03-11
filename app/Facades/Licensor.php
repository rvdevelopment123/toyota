<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Licensor extends Facade
{
    protected static function getFacadeAccessor () {
        return 'licensor';
    }
}
