<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$router->middleware('auth.ion', App\Http\Middleware\AuthenticateWithIONAuth::class);

if (strpos(Request::url(), 'index.php') !== FALSE) {
    header('Location: ' . Config::get("app.url"));
    exit;
}

Route::get('schedule/export', function () {
  
	$dataset = PromoTool::getProgrammingSchedule();
	$schedule = [];
	foreach($dataset as $data) $schedule[] = (array) $data;
	
	Excel::create('Filename', function($excel) use($schedule) {
		$excel->sheet('Sheetname', function($sheet) use($schedule) {
			$sheet->fromArray($schedule);
		});
	})->export('xls');
	  
});

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');

// Authentication routes...
Route::get('auth/login', 'AuthController@getLogin');
Route::post('auth/login', 'AuthController@postLogin');
Route::get('auth/logout', 'AuthController@getLogout');

// Registration routes...
//Route::get('auth/register', 'AuthController@getRegister');
//Route::post('auth/register', 'AuthController@postRegister');

// Auth Group - http://laravel.com/docs/master/routing#route-groups
Route::group(['middleware' => 'auth.ion', 'web'], function () {


    Route::post('upload/endpoint/', 'UploadController@upload');
    Route::post('upload/chunking/endpoint/', 'UploadController@uploadChunks');

    Route::get('activity-logs/{date?}', 'ActivityLogController@index');

    /* SHOWS */
    Route::get('/shows/photos/{id?}', 'PhotoController@index');
    Route::get('/shows/photos/delete/{id}', 'PhotoController@deleteImage');
    Route::get('/shows/episodic-photos/{id?}', 'PhotoController@episodicIndex');

    Route::resource('shows', 'ShowsController');
    Route::get('shows/delete/{id}', 'ShowsController@destroy');

    Route::get('shows/media/create/{id}', 'ShowsController@media');
    Route::get('shows/{id}/featured', 'ShowsController@featured');
    Route::get('image/remove/{id}', 'ImagesController@delete');
    /* --shows/casts-- */
    Route::get('shows/casts/create/{id}', 'CastController@create');
    Route::get('shows/casts/media/create/{id}', 'CastController@media');
    Route::get('shows/casts/{id}', 'CastController@index');
    Route::get('shows/casts/remove-images', 'CastController@getRemoveImages');
    Route::get('shows/casts/delete/{id}', 'CastController@destroy');
    Route::get('shows/remove-images', 'ShowsController@getRemoveImages');
    Route::resource('shows/casts', 'CastController');
    Route::controller('shows/casts', 'CastController');

    Route::resource('casts', 'CastController');

    /* --shows/episodes-- */
    Route::get('shows/episodes/new/{show_id}', 'EpisodeController@create');
    Route::get('shows/episodes/media/create/{id?}', 'EpisodeController@media');
    Route::post('shows/episodes/{id}/edit', 'EpisodeController@edit');
    Route::get('shows/episodes/{id}/delete', 'EpisodeController@destroy');
    Route::get('shows/episodes/images', 'EpisodeController@getEpisodeImages');
    Route::get('shows/episodes/export/{type}/{show_id?}', 'EpisodeController@export');
    Route::patch('shows/episodes/update/{id}', 'EpisodeController@update');
    Route::get('shows/episodes/thumbnail/{id}', 'EpisodeController@updateThumbnail'); // todo: add in file attachment and brightcove response.
    Route::get('shows/episodes/{id}', 'EpisodeController@index');

    Route::resource('episodes', 'EpisodeController');
    Route::resource('shows/episodes', 'EpisodeController');

    Route::get('shows/videos/process/{videoType}/{show_id?}', 'VideosController@processVideos'); // Temp to update brightcove info  todo: remove me
    //Route::get('update/posts/process/{type?}', 'PostsController@processPosts'); //todo: remove me
    Route::get('posts/index/{type_id}', 'PostsController@index');
    Route::post('posts/{id}/edit', 'PostsController@edit');
    Route::resource('posts', 'PostsController');
    Route::get('posts/remove/{id}', 'PostsController@destroy');
    Route::get('posts/videos/delete/{id}', 'VideosController@destroy');
    Route::get('posts/videos/update/{id}', 'PostsController@refreshVideo');

    Route::get('shows/videos/media/create/{id?}', 'VideosController@media');
    Route::get('shows/videos/media/create-bulk/{id?}', 'VideosController@mediaBulk');
    /* --shows/videos-- */

    Route::get('shows/videos/{id}', 'VideosController@index');
    Route::get('shows/videos/delete/{id}', 'VideosController@destroy');
    Route::get('videos/delete/{id}', 'VideosController@destroy');
    Route::get('videos/list/', 'VideosController@listAll');
    Route::get('videos/edit/{id}', 'VideosController@edit');
    Route::post('videos/{id}/edit' ,'VideosController@updateVideoFields' );

    Route::get('shows/videos/secure-video-url/{brightcove_id}', 'VideosController@getSecureVideoURL');
    Route::resource('shows/videos', 'VideosController');


    Route::put('shows/{id}', 'ShowsController@update');
    Route::post('shows/create', 'ShowsController@store');

    Route::controller('shows', 'ShowsController');

    /* CAROUSELS */

    Route::get('carousels/display/{id}/{platform?}', 'CarouselsController@display');
    Route::post('carousels/display/{id}', ['as' => 'carousels.filter', 'uses' => 'CarouselsController@display']);
    Route::put('carousels/add-slide/{id}', ['as' => 'carousels.add-slides', 'uses' => 'CarouselsController@addSlides']);
    Route::resource('carousels', 'CarouselsController');

    //Route::get('carousel-slides/create/{id}', 'CarouselSlidesController@create');
    Route::get('carousel-slides/remove/{id}', 'CarouselSlidesController@destroy');
    Route::get('carousel-slides', 'CarouselSlidesController@index');
    Route::get('carousel-slides/create', ['as' => 'carousel-slides.create', 'uses' => 'CarouselSlidesController@create']);
    Route::post('carousel-slides/create', 'CarouselSlidesController@store');
    Route::get('carousel-slides/{id}', 'CarouselSlidesController@view');

    Route::get('carousel-slides/edit/{id}', ['as' => 'carousel-slides.edit', 'uses' => 'CarouselSlidesController@edit']);
    Route::put('carousel-slides/update/{id}', ['as' => 'carousel-slides.update', 'uses' => 'CarouselSlidesController@update']);
    Route::get('carousel-slides/remove/{id}', 'CarouselSlidesController@destroy');

    Route::get('carousel-schedule/remove/{id}', 'CarouselSlidesController@destroySchedule');


    Route::get('banners/remove/{id}', 'BannersController@destroy');
    Route::resource('banners', 'BannersController');
	
    Route::get('backgrounds/remove/{id}', 'BackgroundController@destroy');
    Route::resource('backgrounds', 'BackgroundController');
	
    Route::resource('settings', 'SettingsController');
    Route::resource('orders', 'OrdersController');
    Route::resource('prizes', 'PrizesController');
    /* USERS */
    Route::resource('users', 'UsersController');
    Route::post('users/edit/{username}', 'UsersController@update_permissions');
    Route::post('users/{id}', 'UsersController@update');
    Route::get('users/remove/{id}', 'UsersController@destroy');
    /* MOVIES */
    Route::resource('movies', 'MoviesController');
    Route::get('movies/media/create/{id}', 'MoviesController@media');
    Route::get('movies/videos/media/create/{id?}', 'VideosController@media');
    Route::get('movies/videos/{id}', 'VideosController@index');
    Route::resource('movies/videos', 'VideosController');

    Route::get('movies/casts/create/{id}', 'CastController@create');
    Route::get('movies/casts/media/create/{id}', 'CastController@media');
    Route::get('movies/casts/{id}', 'CastController@index');
    Route::resource('movies/casts', 'CastController');

    Route::resource('games', 'GamesController');
    Route::get('games/delete/{id}', 'GamesController@destroy');
    Route::controller('games', 'GamesController');


    /*Tags */

    Route::resource('tags', 'TagsController');
    Route::get('tags/delete/{id}/{class}', 'TagsController@destroy');
    Route::get('tags/create/{id}/{tag}/{class}', 'TagsController@create');
    Route::controller('tags', 'TagsController');

	
    Route::resource('rescan-alerts', 'RescanAlertController');
    Route::get('rescan-alerts/videos/update/{id}', 'RescanAlertController@refreshVideo');
    Route::controller('rescan-alerts', 'RescanAlertController');

    /* PAGES */
    Route::resource('pages', 'PagesController');
    Route::get('pages/remove/{id}', 'PagesController@destroy');


    Route::resource('videos', 'VideosController');
    Route::controller('videos', 'VideosController');
    Route::resource('blocks', 'BlocksController');

    /* NIELSEN */
    Route::resource('nielsen', 'NielsenController');
    // think below is wrong work on getting these routes down
    Route::get('nielsen/edit/{id}', 'NielsenController@edit');
    Route::get('nielsen/remove/{id}', 'NielsenController@destroy');

    /* GRIDS */
    Route::get('grids/remove/{id}', 'GridsController@destroy');

    Route::put('grids/add-pods/{id}', ['as' => 'grids.add-pods', 'uses' => 'GridsController@addPods']);
    Route::get('grids/remove-pod/{id}', 'GridsController@removePod');
    Route::get('pods/delete/{id}', 'PodsController@destroy');

    Route::resource('grids', 'GridsController');
    Route::resource('pods', 'PodsController');

    Route::get('grid-cells/remove/{id}', 'GridCellsController@destroy');
    Route::get('grid-cells/{id}/{pod}', 'GridCellsController@edit');
    Route::resource('grid-cells', 'GridCellsController', ['except' => ['edit']]);

    Route::get('grid-layouts/remove/{id}', 'GridLayoutsController@destroy');
    Route::resource('grid-layouts', 'GridLayoutsController');
    Route::get('grid-placements/remove/{id}', 'GridPlacementsController@destroy');
    Route::resource('grid-placements', 'GridPlacementsController');
    Route::controller('grid-placements', 'GridPlacementsController');
    Route::get('grid-cell-types/remove/{id}', 'GridCellTypesController@destroy');
    Route::resource('grid-cell-types', 'GridCellTypesController');



    /* AUDIENCE RELATIONS */
    Route::get('audience-relations/feedbacks/export/{type}/{start_date}/{end_date}', 'FeedbacksController@export');
    Route::get('audience-relations/feedbacks', 'FeedbacksController@index');
    Route::get('audience-relations/feedbacks/create', 'FeedbacksController@create');
    Route::get('audience-relations/feedbacks/remove/{id}', 'FeedbacksController@destroy');
    Route::get('audience-relations/feedbacks/edit/{id}', 'FeedbacksController@edit');
    Route::post('audience-relations/feedbacks/create', 'FeedbacksController@store');
    Route::get('audience-relations/feedbacks/search', 'FeedbacksController@show');
    Route::resource('feedbacks' , 'FeedbacksController');
    Route::controller('audience-relations' , 'FeedbacksController');

    /* NEWSLETTER SUBSCRIBERS */
    Route::get('newsletter/export/{type}', 'NewsletterController@export');
    Route::get('newsletter/remove/{id}', 'NewsletterController@destroy');
    Route::get('newsletter/lists', 'NewsletterController@showAllListsView');
    Route::get('newsletter/list/remove/{id}', 'NewsletterController@listRemove');
    Route::get('newsletter/list/view/{id}', 'NewsletterController@listDetailsView');

    Route::get('newsletter/campaigns/view/{id}', 'NewsletterController@showEmailCampaignsDetail');
    Route::get('newsletter/campaigns', 'NewsletterController@showEmailCampaigns');

    Route::post('newsletter/multiple/{ids?}', 'NewsletterController@multipleDestroy');
    Route::resource('newsletter', 'NewsletterController');
    Route::controller('newsletter', 'NewsletterController');

    /* CHANNEL-FINDER */
    Route::get('channel-finder/{year?}', 'ChannelFinderController@index');

    /* SEARCH LOGS */
    Route::resource('search', 'SearchController');
    
	/** GOOGLE ANALYTICS **/
	Route::get('/google-analytics', 'GoogleAnalyticsController@index');	
	Route::get('/google-analytics/dashboard', 'GoogleAnalyticsController@dashboard');	
	
});

if (!in_array(File::extension(Request::path()), Config::get("route.forbidden_dynamic_extensions"))) {
    //Workaround for now to return a 404 page
    Route::get('{path?}/{path2?}/{path3?}', function () {
        return view('404');
    });

};