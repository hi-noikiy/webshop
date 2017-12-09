<?php

namespace WTG\Models;

use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Block model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Block extends Model
{
    /**
     * Name scope.
     *
     * @param  Builder  $query
     * @param  string  $name
     * @return Builder
     */
    public function scopeName(Builder $query, $name): Builder
    {
        return $query->where('name', $name);
    }

    /**
     * Content attribute mutator.
     *
     * @param  string  $content
     * @return HtmlString
     */
    public function getContentAttribute($content): HtmlString
    {
        return new HtmlString($content);
    }
}