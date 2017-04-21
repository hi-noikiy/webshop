# Laravel WTG 
[![Build Status](https://travis-ci.org/Wiringa-Technische-Groothandel/webshop.svg?branch=master)](https://travis-ci.org/Wiringa-Technische-Groothandel/webshop)
[![StyleCI](https://styleci.io/repos/61391805/shield?branch=master)](https://styleci.io/repos/61391805)

* * *

## Description

[Laravel](http://laravel.com/) implementation of the wiringa.nl webshop.

This webshop is currently live on [wiringa.nl](https://wiringa.nl)

## Installation

* [Install Composer](https://getcomposer.org)

* Clone this repository `git clone https://github.com/Wiringa-Technische-Groothandel/webshop.git laravel-wtg && cd laravel-wtg`

* To download the necessary Laravel components: `composer install` will install the module versions I'm currently using, `composer update` will download the latest modules.

* Copy `.env.example` to `.env`

* Set the correct values in the `.env` file (mail/db/etc.)

* Run `php artisan migrate` to initialize the database

* Install `yarn`

* Get elixir by running `yarn` in the project root folder

* And finally, run `node_modules/.bin/gulp` (dev) / `node_modules/.bin/gulp --production` (minify the css) to copy all the fonts/js/css to the right places

### Laravel Documentation

Documentation for the entire framework can be found on the [Laravel website](http://laravel.com/docs).

### Contributing

[Thomas Wiringa](https://github.com/DuckThom) is the main contributor to this project

### Other licenses

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

The laravel-debugbar by barryvdh license can be found [here](https://github.com/barryvdh/laravel-debugbar/blob/master/LICENSE)

The LaravelShoppingcart by Crinsane license can be found [here](https://github.com/Crinsane/LaravelShoppingcart/blob/master/LICENSE)

The Twitter Bootstrap license can be found [here](https://github.com/twbs/bootstrap/blob/master/LICENSE)

The jQuery license can be found [here](https://jquery.org/license/)

The ChartJS license can be found [here](https://github.com/chartjs/Chart.js/blob/master/LICENSE.md)
