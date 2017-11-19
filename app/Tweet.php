<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Twitter;
use File;
use Log;

class Tweet extends Model
{
    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function media()
    {
        return $this->hasMany('App\Media');
    }

    public static function dispatchAll()
    {
        $now = date('Y-m-d H:i:s');
        $tweets = self::where('schedule', '<=', $now)->where('sent', false)->get();
        $managed = [];

        foreach($tweets as $tweet) {
            try {
                $params = [
                    'status' => $tweet->content,
                    'format' => 'json'
                ];

                $account = $tweet->account;
                Twitter::reconfig(['token' => $account->oauth_token, 'secret' => $account->oauth_secret_token]);

                $media_ids = [];
                foreach($tweet->media as $m) {
                    $uploaded_media = Twitter::uploadMedia(['media' => File::get($m->path)]);
                    $media_ids[] = $uploaded_media->media_id_string;
                }

                if(!empty($media_ids))
                    $params['media_ids'] = join(',', $media_ids);

                Twitter::postTweet($params);
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
