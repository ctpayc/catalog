<?php

namespace Liteweb\Catalog;

use Illuminate\Support\Facades\Facade;

class CatalogFacade extends Facade
{
    protected static function getFacadeAccessor() { 
        return 'liteweb-catalog';
    }
}