<?php

namespace WTG\Http\Controllers;

use Illuminate\View\View;
use WTG\Contracts\Services\CarouselServiceContract;

/**
 * Index controller.
 *
 * @package     WTG\Http
 * @subpackages Controllers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * @var CarouselServiceContract
     */
    protected $carouselService;

    /**
     * IndexController constructor.
     *
     * @param  CarouselServiceContract  $carouselService
     */
    public function __construct(CarouselServiceContract $carouselService)
    {
        $this->carouselService = $carouselService;
    }

    /**
     * Handle the request.
     *
     * @return View
     */
    public function getAction(): View
    {
        $slides = $this->carouselService->getOrderedSlides();

        return view('pages.index', compact('slides'));
    }
}
