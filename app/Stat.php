<?php

namespace App;

use Auth;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    //
    public $type; // the type
    public $active;  // active or not

    public function __contstruct($type)
    {
        $this->type = $type;

    }

    public static function showsCount()
    {
        return Show::series()->active()->get()->count();;
    }

    public static function usersCount()
    {
        return User::count();
    }

    public static function postPerms($user)
    {
        $perms = null;

        return $perms;

    }

}
