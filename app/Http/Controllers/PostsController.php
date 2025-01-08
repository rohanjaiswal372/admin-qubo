<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Post;
use App\PostType;
use Auth;
use App\Video;
use App\Image;
use ContentThatWorks;
use Carbon;

class PostsController extends Controller
{

    public $view_base = 'posts';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index( $type_id = 'qubo-parents' )
    {
        if(!is_null($type_id)) {
            $data = Post::orderBy('title', 'asc')->where('type_id', $type_id)->get();
        }
        else $data = Post::active()->get();
        return view($this->view_base.'.index')->with(['items' => $data]);
    }

    public function show($type_id = null)
    {
        if(!is_null($type_id)) {
            $data = Post::orderBy('title', 'asc')->where('type_id', $type_id)->get();
        }
        else $data = Post::active()->get();
        return view($this->view_base.'.index')->with(['items' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $post_type_selector = PostType::all()->pluck("name", "id")->toArray();
        return view($this->view_base.'.create')->with(compact('post_type_selector'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $post = new Post;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->summary = $request->summary;
        $post->type_id = $request->type_id;
        $post->author = $request->author;
        $post->brightcove_id = $request->brightcove_id;
        $post->pod_tagline = $request->pod_tagline;

        $post->activates_at = Carbon::parse($request->activates_at);


        if( $request->active == 'on' ){
            $post->active = 1;
        }else{
            $post->active = 0;
        }		
        if( empty($request->path) ){
            $post->path = str_slug($request->title);
        }else{
            $post->path = $request->path;
        }
        $post->author_id = Auth::user()->id;
        $post->save();


        //does the video already exsist
        if (is_null($post->video) && $request->brightcove_id) {
            $video = new Video;
            $video->videoable_id = $post->id;
            $video->title = str_limit($post->title, 40);
            $video->videoable_type = 'App\Post';
            $video->type_id = 'default';
            $video->brightcove_id = $request->brightcove_id;
            $video->save();
        }

        $message = "";

        if( $request->file('image') && $this->uploadImage($request,$post,'image','default') ){
            $message .= "<br> Defualt Image has been added";
        }

        if( $request->file('image_secondary') &&  $this->uploadImage($request,$post,'image_secondary','thumbnail')){
            $message .= "<br> Thumbnail Image has been added";
        }

        if( $request->file('image_general') && $this->uploadImage($request,$post,'image_general','general')){
            $message .= "<br> General Image has been added";
        }

        flash()->success("Post has been created<br>".$message);
        return redirect('posts/'.$post->id.'/edit');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $item = Post::with('imageDefault')->with('imageThumbnail')->with('imageGeneral')->findOrFail($id);
        $post_type_selector = PostType::all()->pluck("name", "id")->toArray();
        return view($this->view_base.'.edit')->with(compact('item'))->with(compact('post_type_selector'));
    }

    public function refreshVideo($id)
    {
        $item = Post::with('imageDefault')->with('imageThumbnail')->with('imageGeneral')->findOrFail($id);
        flash()->success('Video has been updated');
        $html = view('templates.partials.videoform')->with(['item' => $item])->render();
        return json_encode(['success' => true, 'html' => $html]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->title = $request->title;
        $post->activates_at = Carbon::parse($request->activates_at);
        if(isset($request->content)) $post->content = $request->content;
        if(isset($request->summary)) $post->summary = $request->summary;
        $post->type_id = $request->type_id;
        $post->author = $request->author;
        $post->pod_tagline = $request->pod_tagline;
        $post->created_at = Carbon::parse($request->created_at);


        //$post->created_at = $request->created_at;
        if( $request->active == 'on' ){
            $post->active = 1;
        }else{
            $post->active = 0;
        }		
        if( empty($request->path) ){
            $post->path = str_slug($request->title);
        }else{
            $post->path = $request->path;
        }
        $post->save();

        //does the video already exsist
        if (is_null($post->video) && $request->brightcove_id ) {
            $video = new Video;
            $video->videoable_id = $post->id;
            $video->title = str_limit($post->title, 40);
            $video->videoable_type = 'App\Post';
            $video->type_id = 'default';
            $video->brightcove_id = $request->brightcove_id;
            $video->save();

        }

        $message = "";

        if( $request->file('image') && $this->uploadImage($request,$post,'image','default') ){
            $message .= "<br> Default Image has been added";
        }

        if( $request->file('image_secondary') &&  $this->uploadImage($request,$post,'image_secondary','thumbnail')){
            $message .= "<br> Thumbnail Image has been added";
        }

        if( $request->file('image_general') && $this->uploadImage($request,$post,'image_general','general')){
            $message .= "<br> General Image has been added";
        }

        flash()->success("Post has been updated<br>".$message);
        return redirect('posts/'.$post->id.'/edit');

        #return redirect(route($this->view_base.'.index'));
    }

    public function getImageUploadDestination($request){
        if( $request->type_id == 'ion-kitchen'){
            return '/insiders/kitchen/blogs/';
        }else{
            return'/insiders/ion-at-home/blogs/';
        }
    }

    private function uploadImage($request, $object, $file_name ='image', $type_id = 'default') {
        if(Image::upload(
            ['model' => get_class($object),
             'object_id' => $object->id,
             'file' => $request->file($file_name),
             'destination' => $this->getImageUploadDestination($request) . $object->path . '-image-' . time() . '.' . $request->file($file_name)->getClientOriginalExtension(),
             'type_id' => $type_id
            ]))
            return TRUE;
        else return FALSE;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if($post->video) {
            $video = Video::findOrFail($post->video->id);
            $video->remove($video->brightcove_id);
            $post->delete();
            flash()->error('This post and video has been removed. ');
        }
        else{
            $post->delete();
            flash()->error('This post has been removed. ');
        }

        return redirect($this->view_base.'/index/'.$post->type_id);
    }

    /**
     * contentThatWorks
     * loads content from the feed for CTW group
     */
    public function contentThatWorks(){
		
		echo("\n Importing Data from ContentThatWorks");
		
        $content = new ContentThatWorks;
        $articles = $content->get();
        foreach( $articles as $article ){
            # check to make sure we have the record already in the system using guid.
            # if not in the system save the record.
			
			echo("\n Article: ".$article['title']);
			
			
            $check_post = Post::where('guid', $article['guid'])->get();
            if( count($check_post) == 0 ){
                # not in the system, lets add it.
                $post = new Post;
				
                $post->title = $article['title'];
                $post->content = $article['content'];
                $post->summary = $article['summary'];
                $post->type_id = 'ion-at-home'; # ion at home article
                $post->active = 0; # should be reviewed first?
                $post->path = str_slug($post->title);
                $post->author_id = 33; # CTW id so we can keep track
                $post->guid = $article['guid'];
                $post->save();
				
				echo("\n +++ Importing");


                # push images up
                if( count($article['images']) > 0 ){
                    $type_id = 'general';
                    # right now just grabbing thumb
                    $image = $article['images'][0];
                    $names = explode('/', $image); # not sure why I have to break this out, php giving error if not.
                    $new_name = array_pop($names);
                    $destination = '/insiders/ion-at-home/ctw/'.$new_name;
                    Image::upload( ['model' => 'App\Post', 'object_id' => $post->id, 'url' => $image, 'destination' => $destination,
                        'type_id' => 'thumbnail'] );
                }
            }else{
				echo("\n --- Article already exists");				
            }

            #echo $article['title'].'<BR> Summary: '.$article['summary'].'<BR><BR>';
            #echo $article['content'].'<hr />';
        }

        return 'finished import';
        #return redirect('/posts/index/ion-at-home');
    }


    public function processPosts($postType = null)
    {
        $this->middleware("auth.ion");
        $debug = [];

        if (Auth::check() && !Auth::user()->hasPermission("brightcove")) {
            flash()->error('You do not have access to this section');
            die(view("noaccess"));
        }
        else {

            $posts = Post::all();

            foreach ($posts as $post) {

                if ($post->video) {
                    $debug[$post->id] = $post->title . "has Video";

                    $video = new Video;
                    $video->videoable_id = $post->id;
                    $video->videoable_type = 'App\Post';
                    $video->brightcove_id = $post->video;
                    $video->type_id = 'default';
                    $video->title = str_limit($post->title, 40);
                    $video->save();
                    $debug[$post->id] = $video;
                }
            }

            return view('templates.output')->with(compact('debug'));
        }
    }
}
