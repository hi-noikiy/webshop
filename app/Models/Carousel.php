<?php

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\CarouselContract;

/**
 * Carousel model.
 *
 * @package     WTG\Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Carousel extends Model implements CarouselContract
{
    /**
     * @var string
     */
    public $table = 'carousel';

    /**
     * Address identifier.
     *
     * @param  null|string  $id
     * @return null|string
     */
    public function identifier(?string $id = null): ?string
    {
        return $this->getAttribute('id');
    }

    /**
     * Get or set the title.
     *
     * @param  null|string  $title
     * @return null|string
     */
    public function title(?string $title = null): ?string
    {
        if ($title) {
            $this->setAttribute('title', $title);
        }

        return $this->getAttribute('title');
    }

    /**
     * Get or set the caption.
     *
     * @param  null|string  $caption
     * @return null|string
     */
    public function caption(?string $caption = null): ?string
    {
        if ($caption) {
            $this->setAttribute('caption', $caption);
        }

        return $this->getAttribute('caption');
    }

    /**
     * Get or set the order.
     *
     * @param  null|int  $order
     * @return null|int
     */
    public function order(?int $order = null): ?int
    {
        if ($order) {
            $this->setAttribute('order', $order);
        }

        return $this->getAttribute('order');
    }

    /**
     * Get or set the image.
     *
     * @param  null|string  $image
     * @return null|string
     */
    public function image(?string $image = null): ?string
    {
        if ($image) {
            $this->setAttribute('image', $image);
        }

        return $this->getAttribute('image');
    }
}