<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \LDAP;
use Auth;
use \Hash;
use \App\User;
use \App\UserPermission;
use \App\UserPermissionLevel;

class UsersController extends Controller
{
    /**
     * Check if user has admin edit rights
     *
     * @return die
     */
    public function __construct()
    {
        if (Auth::check() && !Auth::user()->hasPermission("admin")) {
            flash()->error('You do not have access to this section');
            die(view("noaccess"));
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $active_users = User::where("active", "=", 1)->get();
        return view('users.index')->with('active_users', $active_users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $active_usernames = User::where("active", "=", 1)->get()->pluck("username")->toArray();

        $ion_users = [];
        $users = [];

        foreach (LDAP::all()->users as $user) {
            if (!in_array($user->username, $active_usernames)) {
                $ion_users[$user->username] = $user->firstname . " " . $user->lastname;
            }
        }
        return view('users.create')->with('ion_users', $ion_users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function store(Requests\CreateNewUserRequest $request)
    {
        $user = new User;

        switch ($request->type_id) {

            case "ion":

                foreach (LDAP::all()->users as $ion_user) {

                    if ($request->ion_username == $ion_user->username) {
                        $user->username = $ion_user->username;
                        $user->firstname = $ion_user->firstname;
                        $user->lastname = $ion_user->lastname;
                        $user->email = $ion_user->firstname . $ion_user->lastname . "@ionmedia.com";
                        $user->type_id = $request->type_id;
                        $user->active = 1;
                        $user->password = Hash::make($user->username . "@" . strtotime("now"));
                        break;
                    }
                }


                break;
            case "local":
                $user->type_id = $request->type_id;
                $user->username = $request->username;
                $user->firstname = $request->firstname;
                $user->lastname = $request->lastname;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->active = 1;
                break;

        }

        $user->save();
        flash()->success('New users has been created.');
        return redirect(route('users.index'));


    }

    /**
     * updates the users permissions settings.
     *
     * @param  request , username
     *
     * @return Response
     */
    public function update_permissions(Request $request, $username)
    {
        if ($request->permissions) {
            UserPermission::where("username", $username)->delete();
            foreach ($request->permissions as $permission) {
                $new_permission = new UserPermission();
                $new_permission->username = $username;
                $new_permission->permission = $permission;
                $new_permission->save();
            }
        }

        flash()->success("<strong>".$username."</strong> permission's have been updated");
        return redirect(route('users.index'));
    }

    public function updateUserSettings($key){

        $userid = Auth::user()->id;
        //$key = str_slug($show->name);

        //page_url

        //        setting()->set($key, 'Visited', $userid);
        //        setting()->save($userid);
        //        dd(setting()->has($key, $userid));
        setting()->set($key, 'Last Visited '.Carbon::now()->toDateTimeString(), $userid);
        setting()->save($userid);

        if(setting()->has($key, $userid)){
            $settings = setting()->get($key, 'default', $userid);
        }
        else{
            setting()->set($key, 'Visited'.Carbon::now()->toDateTimeString(), $userid);
            setting()->save($userid);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($username)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($username)
    {
        $user = User::get($username);
        $permission_levels = UserPermissionLevel::all();
        return view('users.edit')->with('user', $user)->with('permission_levels', $permission_levels);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int     $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->id = $request->id;
        $user->type_id = $request->type_id;
        $user->username = $request->username;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->active = 1;
        $user->save();
        flash()->success('User has been updated');
        return redirect(route('users.index'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $id = User::find($id);
        $id->delete();
        flash()->error('User has been deleted');
        return redirect(route('users.index'));
        # archive?
    }
}
