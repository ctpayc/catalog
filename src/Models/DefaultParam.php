<?php

namespace Liteweb\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultParam extends Model
{

    protected $fillable = ['id', 'type', 'name', 'params'];

    protected $casts = [
        'params' => 'array'
    ];
}
