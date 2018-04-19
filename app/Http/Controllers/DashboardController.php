<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use DB;
use Log;
use Redirect;

use App\Account;
use App\Tweet;
use App\Media;

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
            DB::beginTransaction();

            $this->validate($request, [
                'account' => 'required',
                'schedule' => 'required',
            ]);

            $user = Auth::user();
            $account = Account::find($request->input('account'));
            if ($account->user_id != $user->id) {
                return Redirect::to('dashboard')->with('feedback', 'Not Authorized');
            }

            $edit = $request->input('tweet', '');
            if (!empty($edit)) {
                $tweet = Tweet::find($edit);
                if ($tweet->account->user_id != $user->id) {
                    return Redirect::to('dashboard')->with('feedback', 'Not Authorized');
                }
            }
            else {
                $tweet = new Tweet();
            }

            $tweet->account_id = $account->id;

            $tweet->content = trim($request->input('content'));
            if (empty($tweet->content)) {
                $tweet->content = '';
                $tweet->retweet = $request->input('retweet');
                $feedback = 'Retweet scheduled!';
            }
            else {
                $tweet->retweet = '';
                $feedback = 'Tweet scheduled!';
            }

            $tweet->schedule = $request->input('schedule');
            $tweet->save();

            $media_ids = $request->input('keep_media', []);
            $attached_media = $request->file('media');
            if (is_array($attached_media)) {
                foreach ($attached_media as $media) {
                    $folder = storage_path() . '/media/';
                    do {
                        $filename = rand();
                        $path = $folder . '/' . $filename;
                    } while (file_exists($path));

                    $media->move($folder, $filename);

                    $m = new Media();
                    $m->tweet_id = $tweet->id;
                    $m->path = $path;
                    $m->save();
                    $media_ids[] = $m->id;
                }
            }

            $tweet->media()->whereNotIn('id', $media_ids)->delete();

            DB::commit();
            return Redirect::to('dashboard')->with('feedback', $feedback);
        }
        catch(\Exception $e) {
            Log::error('Error saving tweet: ' . $e->getMessage());
            return Redirect::to('dashboard')->with('feedback', 'An error occourred, please retry');
        }
    }

    public function accountRemove(Request $request, $id)
    {
        try {
            $user = Auth::user();

            $account = Account::find($id);
            if ($account != null && $account->user_id == $user->id) {
                $account->tweets()->delete();
                $account->delete();

                if ($user->accounts()->count() > 0) {
                    return Redirect::to('dashboard')->with('feedback', 'Account removed!');
                }
                else {
                    $user->delete();
                    Auth::logout();
                    return Redirect::to('/');
                }
            }
            else {
                return Redirect::to('dashboard')->with('feedback', 'Not Authorized');
            }
        }
        catch(\Exception $e) {
            Log::error('Error deleting account: ' . $e->getMessage());
            return Redirect::to('dashboard')->with('feedback', 'An error occourred, please retry');
        }
    }
}
