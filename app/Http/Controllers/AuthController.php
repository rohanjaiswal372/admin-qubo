<?php

namespace App\Http\Controllers;

use Auth;
use cURL;
use LDAP;
use Session;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function postLogin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        if ( is_email($username) ) {
            try {
                $username = \App\User::where('email',$username)->first()->username;
            }
            catch (\Exception $e) {
                Bugsnag::notifyException($e);
            }
        }

        $login = ['username'=>$username,'password'=>$password];

        if ( LDAP::attempt($login) || Auth::attempt($login) ) {
            //Authentication passed...
            return redirect()->intended('home');
        }

        return redirect('/auth/login')->withErrors([
            'error' => 'These credentials do not match our records.',
        ]);

    }

	public function getLogin()
    {
		Session::forget('logged_in');
		return view("auth.login");
	}

	public function getLogout()
    {
		Auth::logout();
		Session::forget('logged_in');
		return redirect("auth/login");
	}

}
