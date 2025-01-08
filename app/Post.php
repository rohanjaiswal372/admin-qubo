<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use URL;
use Carbon;
use Auth;

class Post extends Model
{
    use Traits\ActivityLog\ActivityLogTrait;

    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'content', 'path', 'summary', 'author_id', 'type_id'];

    public function images()
    {
        return $this->morphMany('App\Image', 'imageable');
    }

    public function type()
    {

        return $this->hasOne('App\PostType', 'id', 'type_id');

    }

    public function imageDefault()
    {
        return $this->morphMany('App\Image', 'imageable')->where('type_id', '=', 'default');
    }

    public function imageThumbnail()
    {
        return $this->morphMany('App\Image', 'imageable')->where('type_id', '=', 'thumbnail');
    }

    public function imageGeneral()
    {
        return $this->morphMany('App\Image', 'imageable')->where('type_id', '=', 'general');
    }

    public function sponsor_logo()
    {
        $query = $this->morphOne('App\Advertisement', 'morphable')->where("starts_at", "<=", Carbon::now())->where("ends_at", ">=", Carbon::now())->where("type_id", 1)->whereIn('category_id', AdvertisementCategory::where("platform_id", 1)->lists("id")->toArray())->orderBy("id", "desc");
        if (\App::environment('production'))
            $query->where("active", 1);
        return $query;
    }

    public function video()
    {
        return $this->morphOne('App\Video', 'videoable');
    }

    public function url()
    {

        switch ($this->type_id) {
            case "ion-kitchen":
                return "/insiders/kitchen/chef-blog/" . $this->path;
                break;
            case "ion-at-home":
                return "/insiders/ion-at-home/blog/" . $this->path;
                break;
            case "ion-at-home-projects":
                return "/insiders/ion-at-home/martins-workshop/" . $this->path;
                break;
            case "good-works":
                return "/insiders/give-hope/good-works/" . $this->path;
                break;
        }

        return "";
    }

    public function scopeHope($query)
    {
        $query->where("type_id", "=", "good-works");
    }

    public function scopeHome($query)
    {
        $query->where("type_id", "=", "ion-at-home");
    }

    public function scopeProjects($query)
    {
        $query->where("type_id", "=", "ion-at-home-projects");
    }

    public function scopeKitchen($query)
    {
        $query->where("type_id", "=", "ion-kitchen");
    }

    public function scopeActive($query)
    {
        $query->where("active", "=", 1);
    }

    public function scopeRecent($query)
    {

        $query->where('created_at', '>', Carbon::now()->subDays(30))->orderBy('created_at', 'desc');

    }

    public function sponsor_banner()
    {
        $query = $this->morphOne('App\Advertisement', 'morphable')->where("starts_at", "<=", Carbon::now())->where("ends_at", ">=", Carbon::now())->where("type_id", 2)->whereIn('category_id', AdvertisementCategory::where("platform_id", 1)->lists("id")->toArray())->orderBy("id", "desc");

        if (\App::environment('production'))
            $query->where("active", 1);
        return $query;
    }

    public function sponsor_pod()
    {
        $query = $this->morphOne('App\Advertisement', 'morphable')->where("starts_at", "<=", Carbon::now())->where("ends_at", ">=", Carbon::now())->where("type_id", 3)->whereIn('category_id', AdvertisementCategory::where("platform_id", 1)->lists("id")->toArray())->orderBy("id", "desc");

        if (\App::environment('production'))
            $query->where("active", 1);
        return $query;
    }

    public function ads()
    {
        $query = $this->morphMany('App\Advertisement', 'morphable')->where("starts_at", "<=", Carbon::now())->where("ends_at", ">=", Carbon::now())->whereIn('category_id', AdvertisementCategory::where("platform_id", 1)->lists("id")->toArray())->orderBy("id", "desc");

        if (\App::environment('production'))
            $query->where("active", 1);
        return $query;
    }
    public function getComscoreFieldsAttribute(){
        return [
            "showName" => $this->title,
            "episodeTitle" => $this->path,
            "episodeNumber" => $this->id,
            "videoType" => "Client Solutions",
            "rating" => "*null",
            "season" => "*null"
        ];
    }

}
