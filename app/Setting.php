<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'settings';
	protected $primaryKey = 'id';
	protected $fillable = ['value', 'setting', 'on_off'];

}
