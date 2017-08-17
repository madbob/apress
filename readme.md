## apress

**apress** is a simple Twitter scheduler.

You can use [my public instance](http://apress.madbob.org/) (no warranties!) or host for your own.

# Installation

```
git clone https://github.com/madbob/apress
cd apress
composer install
cp .env.example .env
(create a new database, and edit .env accordly to your setup)
php artisan migrate
php artisan key:generate
```

Then...

You need a Twitter app keypair: [register it here](https://apps.twitter.com/) and save in the `.env` file. Use `http://yourowninstance.com/twitter/callback` as authentication callback.

The scheduling operations can be performed by the `php artisan dispatch` command (to be put on `cron` at the preferred interval, 10 minutes is suggested) or calling `http://yourowninstance.com/dispatch?key=YourSecretKey` through a webcron service. You can set your own secret key in the `REMOTE_KEY` attribute in `.env` file.

# License

**apress** is licensed under the AGPLv3+ license.

Copyright (C) 2017 Roberto Guido <bob@linux.it>
