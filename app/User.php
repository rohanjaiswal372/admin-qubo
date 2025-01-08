<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Traits\ActivityLog\ActivityLogTrait;

    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
	
	public static function get($id = null){
		if(is_null($id)) return;
		return self::select($id)->first();
	}

	public function scopeSelect($query, $param){
				
		if(is_numeric($param)){
			$query->where("id","=",$param);
		}else{
			$query->where("username","=",$param);
		}
	}	
	
	public function permissions(){
		return $this->hasMany("App\UserPermission","username","username");
	}

	public function getFullNameAttribute(){
		return $this->firstname." ".$this->lastname;
	}
	
	public function hasPermission($permission){
		foreach($this->permissions as $granted){
			if($granted->permission == $permission){
				return true;
			}
		}
		return false;
	}
    public function hasGroup($group){
        return ($this->group_id == $group) ? true : false;
    }

	public function campaignsFollowed(){

			return $this->hasMany("App\CampaignFollower", "user_id", "id");
	}
	public function campaigns(){

		return $this->hasMany("App\Campaign", 'user_id', 'id');
	}
	
	public function group(){
		return $this->hasOne("App\UserGroup","id","group_id");
	}

    public static function getGroup($group){

        return self::groupMember($group);
    }
    public function scopeGroupMember($query,$group){
        $query->where('group_id',$group);
    }
	
	public function type(){
		return $this->hasOne("App\UserType","id","type_id");
	}		
}
