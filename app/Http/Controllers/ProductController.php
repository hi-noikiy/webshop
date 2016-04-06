<?php namespace App\Http\Controllers;

use App\Pack;

class ProductController extends Controller {

    /**
     * Pack details
     *
     * @param int $pack_id
     * @return mixed
     */
    public function showPack(int $pack_id)
    {
        return view('webshop.pack', [
            'pack' => Pack::find($pack_id)
        ]);
    }

}