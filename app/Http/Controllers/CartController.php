<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pack;
use App\User;

use Auth, Session, Cart;

class CartController extends Controller {

    public function addPack(Request $request)
    {
        $id  = $request->get('pack');

        $validator = \Validator::make($request->all(), [
            'pack' => 'required'
        ]);

        if (!$validator->fails()) {
            // Load the product data
            $pack = Pack::find($id);
            $products = $pack->products;
            $price = 0.00;

            // Load the user cart data
            $cartArray = unserialize(Auth::user()->cart);

            foreach ($products as $product)
            {
                $product = $product->details;

                $price = number_format((preg_replace("/\,/", ".", $product->price) * $product->refactor) / $product->price_per, 2, ".", "");
            }

            // Add the product data to the cart data
            $cartArray[$id] =
            $productData = [
                'id' => $id,
                'name' => $pack->name,
                'qty' => 1,
                'price' => number_format($price, 2, ".", ""),
                'options' => [
                    'special' => true,
                ]
            ];

            // Add the product to the cart
            Cart::add($productData);

            // Save the updated array to the database
            $user = User::find(Auth::user()->id);
            $user->cart = serialize($cartArray);
            $user->save();

            if (Session::has('continueShopping'))
                return redirect(Session::get('continueShopping'))->with('status', 'Het product ' . $number . ' is toegevoegd aan uw winkelwagen');
            else
                return redirect('cart');
        } else
            return redirect()->back()
                ->withErrors($validator->errors());
    }

}