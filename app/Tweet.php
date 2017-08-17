<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Twitter;
use Log;

class Tweet extends Model
{
    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public static function dispatchAll()
    {
        $now = date('Y-m-d H:i:s');
        $tweets = self::where('schedule', '<=', $now)->where('sent', false)->get();
        $managed = [];

        foreach($tweets as $tweet) {
            try {
                $account = $tweet->account;
                Twitter::reconfig(['token' => $account->oauth_token, 'secret' => $account->oauth_secret_token]);
                Twitter::postTweet(['status' => $tweet->content, 'format' => 'json']);
            }
            catch(\Exception $e) {
                Log::error('Error sending tweet ' . $tweet->id . ': ' . $e->getMessage());
                $tweet->error = $e->getMessage();
                $tweet->save();
            }

            $managed[] = $tweet->id;
            usleep(500000);
        }

        self::whereIn('id', $managed)->update(['sent' => true]);
    }
}
