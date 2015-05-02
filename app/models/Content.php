<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Content extends Eloquent implements UserInterface, RemindableInterface {

        use UserTrait, RemindableTrait;

        /**
         * The database table used by the model.
         *
         * @var string
         */
        protected $table = 'text';

        /**
         * The guarded columns in the table
         *
         * @var array
         */
        protected $guarded = array('id', 'name');

}
