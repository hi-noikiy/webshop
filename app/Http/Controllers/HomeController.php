<?php

namespace App\Http\Controllers;

use WTG\Block\Interfaces\BlockInterface;

/**
 * Home controller.
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class HomeController extends Controller
{
    /**
     * Homepage.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        $news = app()->make(BlockInterface::class)->getByTag('home.news')->getContent();

        return view('home.index', compact('news'));
    }

    /**
     * About us.
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return view('home.about');
    }

    /**
     * Assortment page.
     *
     * @return \Illuminate\View\View
     */
    public function assortment()
    {
        $manufacturers = json_decode(file_get_contents(base_path('resources/assets/json/manufacturers.json')));

        return view('home.assortment', [
            'manufacturers' => $manufacturers,
        ]);
    }

    /**
     * Contact info.
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('home.contact');
    }

    /**
     * Downloads.
     *
     * @return \Illuminate\View\View
     */
    public function downloads()
    {
        return view('home.downloads');
    }

    /**
     * Licenses.
     *
     * @return \Illuminate\View\View
     */
    public function licenses()
    {
        return view('home.licenses');
    }

    /**
     * Show a pdf inside a view.
     *
     * @param  string  $filename
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($filename)
    {
        return app('download')
            ->path(public_path("dl/{$filename}"))
            ->as($filename)
            ->serve();
    }
}
