<?php

namespace WTG\Contracts\Models;

/**
 * Carousel contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface CarouselContract
{
    /**
     * Address identifier.
     *
     * @param  null|string  $id
     * @return null|string
     */
    public function identifier(?string $id = null): ?string;

    /**
     * Get or set the title.
     *
     * @param  null|string  $title
     * @return null|string
     */
    public function title(?string $title = null): ?string;

    /**
     * Get or set the caption.
     *
     * @param  null|string  $caption
     * @return null|string
     */
    public function caption(?string $caption = null): ?string;

    /**
     * Get or set the order.
     *
     * @param  null|int  $order
     * @return null|int
     */
    public function order(?int $order = null): ?int;

    /**
     * Get or set the image.
     *
     * @param  null|string  $image
     * @return null|string
     */
    public function image(?string $image = null): ?string;
}