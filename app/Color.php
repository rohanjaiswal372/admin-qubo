<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $table = 'colors';
    protected $primaryKey = 'id';
    protected $fillable = ['type_id', 'code', 'colorable_id'];
    public $timestamps = false;
    public $incrementing = false;

    public function type(){
        return $this->hasOne("App\ColorType","id","type_id");
    }
}
