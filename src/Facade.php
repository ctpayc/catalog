<?php
// src/DemoFacade.php

namespace Liteweb\Catalog;

use Illuminate\Support\Facades\Facade;

class DemoFacade extends Facade
{
    protected static function getFacadeAccessor() { 
        return 'liteweb-catalog';
    }
}