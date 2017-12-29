<?php

namespace WTG\Services;

use WTG\Models\Carousel;
use Illuminate\Support\Collection;
use WTG\Contracts\Services\CarouselServiceContract;

/**
 * Carousel service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CarouselService implements CarouselServiceContract
{
    /**
     * Get a collection of ordered carousel models.
     *
     * @return Collection
     */
    public function getOrderedSlides(): Collection
    {
        return Carousel::orderBy('order', 'asc')->get();
    }
}