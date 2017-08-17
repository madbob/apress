<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public function accounts()
    {
        return $this->hasMany('App\Account');
    }

    public function tweets()
    {
        return $this->hasManyThrough('App\Tweet', 'App\Account')->where('sent', false)->orderBy('schedule', 'asc');
    }
}
