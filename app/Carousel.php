<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Carousel.
 *
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $Image
 * @property string $Title
 * @property string $Caption
 * @property int $Order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Carousel whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Carousel whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Carousel whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Carousel whereCaption($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Carousel whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Carousel whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Carousel whereUpdatedAt($value)
 */
class Carousel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'carousel';

    /**
     * The guarded columns in the table.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
