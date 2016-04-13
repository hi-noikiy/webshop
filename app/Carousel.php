<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Carousel
 * @package App
 */
class Carousel extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'carousel';

    /**
     * The guarded columns in the table
     *
     * @var array
     */
    protected $guarded = ['id'];

}
