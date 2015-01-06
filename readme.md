# Laravel WTG

* * *

## Description

[Laravel](http://laravel.com/) implementation of the wiringa.nl webshop.

The webshop is currently written with CodeIgniter by me.

In order to learn the Laravel framework, I am trying to rewrite the website/webshop in Laravel.

## Installation

* [Install Composer](https://getcomposer.org)

* Clone this repository `git clone https://github.com/DuckThom/laravel-wtg.git`

* `cd laravel-wtg && composer update` To download the neccesary Laravel components.

* Rename 'example.env.php' to either:

    * `env.local.php` for local development
    
    * `env.staging.php` for staging - **NOTE: go to** `bootstrap/start.php` **and set the hostnames for staging and production to the appropriate values**
    
    * `.env.php` or `.env.production.php` for production **NOTE: Laravel will default to** `.env.php` **when it doesn't match any hostname in** `bootstrap/start.php`
    
* Set the correct values in the .env.php files (mail/db/etc.)

* Run 'php artisan migrate' to initialize the database

* Happy coding!

### Laravel Documentation

Documentation for the entire framework can be found on the [Laravel website](http://laravel.com/docs).

### Contributing to this project

**All pull requests will be rejected!**
[DuckThom](https://github.com/DuckThom) is the only contributor for this project.

### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
The laravel-debugbar by barryvdh license can be found [here](https://github.com/barryvdh/laravel-debugbar/blob/master/LICENSE)
