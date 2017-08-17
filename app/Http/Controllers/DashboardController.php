<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Redirect;

use App\Account;
use App\Tweet;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function homepage(Request $request)
    {
        $user = Auth::user();

        $edit = $request->input('edit', null);
        if ($edit != null) {
            $edit = Tweet::find($edit);
            if ($edit != null && $edit->account->user_id != $user->id)
                $edit = null;
        }

        $remove = $request->input('remove', null);
        if ($remove != null) {
            $remove = Tweet::find($remove);
            if ($remove != null && $remove->account->user_id == $user->id)
                $remove->delete();
        }

        return view('pages.dashboard', ['user' => $user, 'edit' => $edit]);
    }

    public function save(Request $request)
    {
        try {
            $this->validate($request, [
                'account' => 'required',
                'content' => 'required',
                'schedule' => 'required',
            ]);

            $user = Auth::user();
            $account = Account::find($request->input('account'));
            if ($account->user_id != $user->id) {
                return Redirect::to('dashboard')->with('feedback', 'Not Authorized');
            }

            $edit = $request->input('edit', '');
            if (!empty($edit))
                $tweet = Tweet::find($edit);
            else
                $tweet = new Tweet();

            $tweet->account_id = $account->id;
            $tweet->content = $request->input('content');
            $tweet->schedule = $request->input('schedule');
            $tweet->save();

            return Redirect::to('dashboard')->with('feedback', 'Tweet scheduled!');
        }
        catch(\Exception $e) {
            return Redirect::to('dashboard')->with('feedback', 'An error occourred, please retry');
        }
    }
}
