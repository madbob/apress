<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Tweet;

class RunQueue extends Command
{
    protected $signature = 'dispatch';
    protected $description = 'Publish scheduled tweets';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Tweet::dispatchAll();
    }
}
