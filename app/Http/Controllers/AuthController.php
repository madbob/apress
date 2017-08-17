<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use Redirect;
use Twitter;

use App\User;
use App\Account;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $sign_in_twitter = true;
        $force_login = (Auth::user() != null);

        Twitter::reconfig(['token' => '', 'secret' => '']);
        $token = Twitter::getRequestToken(route('twitter.callback'));

        if (isset($token['oauth_token_secret'])) {
            $url = Twitter::getAuthorizeURL($token, $sign_in_twitter, $force_login);

            Session::put('oauth_state', 'start');
            Session::put('oauth_request_token', $token['oauth_token']);
            Session::put('oauth_request_token_secret', $token['oauth_token_secret']);

            return Redirect::to($url);
        }

        return Redirect::route('twitter.error');
    }

    public function callback(Request $request)
    {
        if (Session::has('oauth_request_token')) {
            $request_token = [
                'token'  => Session::get('oauth_request_token'),
                'secret' => Session::get('oauth_request_token_secret'),
            ];

            Twitter::reconfig($request_token);

            $oauth_verifier = false;

            if ($request->has('oauth_verifier')) {
                $oauth_verifier = $request->input('oauth_verifier');
                $token = Twitter::getAccessToken($oauth_verifier);
            }

            if (!isset($token['oauth_token_secret'])) {
                return Redirect::route('twitter.error');
            }

            $credentials = Twitter::getCredentials();

            if (is_object($credentials) && !isset($credentials->error)) {
                $user = Auth::user();

                if ($user == null) {
                    $account = Account::where('handle', $credentials->screen_name)->first();

                    if ($account == null) {
                        $user = new User();
                        $user->save();

                        $account = new Account();
                        $account->user_id = $user->id;
                        $account->handle = $credentials->screen_name;
                        $account->oauth_token = $token['oauth_token'];
                        $account->oauth_secret_token = $token['oauth_token_secret'];
                        $account->picture_url = $credentials->profile_image_url;
                        $account->save();
                    }
                    else {
                        $account->oauth_token = $token['oauth_token'];
                        $account->oauth_secret_token = $token['oauth_token_secret'];
                        $account->picture_url = $credentials->profile_image_url;
                        $account->save();

                        $user = $account->user;
                    }

                    Auth::login($user);
                }
                else {
                    $account = Account::where('handle', $credentials->screen_name)->first();

                    if ($account == null) {
                        $account = new Account();
                        $account->user_id = $user->id;
                        $account->handle = $credentials->screen_name;
                        $account->oauth_token = $token['oauth_token'];
                        $account->oauth_secret_token = $token['oauth_token_secret'];
                        $account->picture_url = $credentials->profile_image_url;
                        $account->save();
                    }
                    else {
                        $account->user_id = $user->id;
                        $account->token = $token;
                        $account->picture_url = $credentials->profile_image_url;
                        $account->save();
                    }
                }

                Session::put('access_token', $token);
                return Redirect::to('dashboard');
            }

            return Redirect::route('twitter.error');
        }
    }

    public function logout(Request $request)
    {
        Session::forget('access_token');
        Auth::logout();
        return Redirect::to('/');
    }
}
