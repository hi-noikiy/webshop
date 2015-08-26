# Laravel WTG

* * *

## Description

[Laravel](http://laravel.com/) implementation of the wiringa.nl webshop.

The webshop is currently written with CodeIgniter by me.

In order to learn the Laravel framework, I am trying to rewrite the website/webshop in Laravel.

## Installation

* [Install Composer](https://getcomposer.org)

* Clone this repository `git clone https://github.com/DuckThom/laravel-wtg.git`

* `cd laravel-wtg && composer update` (`composer install` will install the module versions I'm currently using, `update` will download the latest modules) To download the neccesary Laravel components.

* Rename '.env.example' to either:

    * `.env.local` for local development
    
    * `.env.staging` for staging
    
    * `.env` or `.env.production` for production **NOTE: Laravel will default to** `.env` **when it doesn't match any hostname in** `bootstrap/start.php`
    
* Set the correct values in the .env files (mail/db/etc.)

* Run 'php artisan migrate' to initialize the database

* Happy coding!

### Laravel Documentation

Documentation for the entire framework can be found on the [Laravel website](http://laravel.com/docs).

### Contributing to this project

**All pull requests will be rejected!**
[DuckThom](https://github.com/DuckThom) is the only contributor for this project.

### Other licenses

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

The laravel-debugbar by barryvdh license can be found [here](https://github.com/barryvdh/laravel-debugbar/blob/master/LICENSE)

The LaravelShoppingcart by Crinsane license can be found [here](https://github.com/Crinsane/LaravelShoppingcart/blob/master/LICENSE)

The Twitter Bootstrap license can be found [here](https://github.com/twbs/bootstrap/blob/master/LICENSE)

The jQuery license can be found [here](https://jquery.org/license/)

The jQuery Toaster license can be found [here](https://github.com/scottoffen/jquery.toaster/blob/master/LICENSE)
