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
    
    * `env.staging.php` for staging - **NOTE: edit** `bootstrap/start.php` **and set the hostnames for staging and production to the appropriate values**
    
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

Released under the MIT license.

Copyright (C) 2015 Thomas Wiringa

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

* * *

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

The laravel-debugbar by barryvdh license can be found [here](https://github.com/barryvdh/laravel-debugbar/blob/master/LICENSE)

The LaravelShoppingcart by Crinsane license can be found [here](https://github.com/Crinsane/LaravelShoppingcart/blob/master/LICENSE)

The Twitter Bootstrap license can be found [here](https://github.com/twbs/bootstrap/blob/master/LICENSE)