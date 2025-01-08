<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ColorType extends Model
{
    protected $table = 'color_types';
    protected $primaryKey = 'id';
    public $incrementing = false;
}
