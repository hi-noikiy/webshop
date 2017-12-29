<?php

namespace WTG\Contracts\Services;

use Illuminate\Support\Collection;

/**
 * Carousel service contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface CarouselServiceContract
{
    /**
     * Get a collection of ordered carousel models.
     *
     * @return Collection
     */
    public function getOrderedSlides(): Collection;
}