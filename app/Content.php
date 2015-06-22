<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model {

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
