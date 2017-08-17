<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Tweet;

class CommonsController extends Controller
{
    public function homepage()
    {
        $user = Auth::user();
        if ($user != null)
            return redirect()->action('DashboardController@homepage');

        return view('pages.homepage');
    }

    public function dispatchAll(Request $request)
    {
        $key = $request->input('key', '');
        if ($key != env('REMOTE_KEY', str_random(30)))
            abort(403);

        Tweet::dispatchAll();
    }
}
