<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Content
 *
 * @package App
 * @mixin \Eloquent
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $page
 * @property mixed $content
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property boolean $visible
 * @property boolean $hidden
 * @property boolean $error
 * @method static \Illuminate\Database\Query\Builder|\App\Content whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Content whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Content whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Content wherePage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Content whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Content whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Content whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Content whereVisible($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Content whereHidden($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Content whereError($value)
 */
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
    protected $guarded = ['id', 'name'];

}
