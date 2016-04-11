<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pack;
use App\User;
use App\Product;
use App\Helper;
use App\Order;
use App\Address;
use Auth, Session, Cart, DB, Input;

class CartController extends Controller {

    /**
     * Show the cart
     *
     * @return mixed
     */
    public function view()
    {
        if (Auth::check())
            $addresses = DB::table('addresses')->where('User_id', Auth::user()->login)->get();
        else
            return redirect('/#loginModal');

        return view('webshop.cart', [
            'cart' => Cart::content(),
            'addresses' => $addresses
        ]);
    }

    /**
     * Add a product to the cart
     *
     * @param Request $request
     * @return mixed
     */
    public function addProduct(Request $request)
    {
        $number = $request->get('product');
        $qty    = $request->get('qty');

        $validator = \Validator::make($request->all(), [
            'product' => 'required|digits:7',
            'qty' => 'required|numeric'
        ]);

        if (!$validator->fails()) {
            // Load the product data
            $product = Product::where('number', $number)->firstOrFail();
            // Load the user cart data
            $cartArray = unserialize(Auth::user()->cart);

            // Add the product data to the cart data
            $cartArray[$number] =
            $productData = [
                'id' => $product->number,
                'name' => $product->name,
                'qty' => $qty,
                'price' => number_format((preg_replace("/\,/", ".", $product->price) * $product->refactor) / $product->price_per, 2, ".", ""),
                'options' => [
                    'special' => false,
                    'korting' => Helper::getProductDiscount(Auth::user()->login, $product->group, $product->number)
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
            return redirect()
                ->back()
                ->withErrors($validator->errors());
    }

    /**
     * Add a product pack to the cart
     *
     * @param Request $request
     * @return mixed
     */
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

                $price += number_format((preg_replace("/\,/", ".", $product->price) * $product->refactor) / $product->price_per, 2, ".", "");
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
                    'products' => $products
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

    /**
     * Modify or remove a product from the cart
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $rowId  = $request->get('rowId');
        $qty    = $request->get('qty');
        $artNr  = $request->get('productId');

        $validator = \Validator::make($request->all(), [
            'rowId' => 'required',
            'qty' => 'required|numeric'
        ]);

        if (!$validator->fails()) {
            if (Input::get('edit') === "") {
                // Load the user cart data
                $cartArray = unserialize(Auth::user()->cart);

                $cartArray[$artNr]['qty'] = $qty;

                // Save the updated array to the database
                $user = User::find(Auth::user()->id);
                $user->cart = serialize($cartArray);
                $user->save();

                Cart::update($rowId, ['qty' => $qty]);

                return redirect('cart')->with('status', 'Uw winkelwagen is geupdatet');
            } elseif (Input::get('remove') === "") {
                // Load the user cart data
                $cartArray = unserialize(Auth::user()->cart);

                unset($cartArray[$artNr]);

                // Save the updated array to the database
                $user = User::find(Auth::user()->id);
                $user->cart = serialize($cartArray);
                $user->save();

                Cart::remove($rowId);

                return redirect('cart')->with('status', 'Het product is verwijderd');
            } else
                return redirect('cart')->withErrors('Er is een fout opgetreden');
        } else {
            $messages = $validator->errors();
            $msg = '';

            // Put all the messages in one variable
            foreach ($messages->all() as $key => $message)
                $msg .= ucfirst($message) . "<br />";

            return redirect('cart')->withErrors($msg);
        }
    }

    /**
     * To destroy or not to destroy
     *
     * @return mixed
     */
    public function destroy()
    {
        // Cart::destroy() returns NULL, issue:
        // https://github.com/Crinsane/LaravelShoppingcart/issues/56
        if (!Cart::destroy()) {
            $user = User::find(Auth::user()->id);
            $user->cart = NULL;
            $user->save();

            return redirect('/')->with('status', 'Uw winkelwagen is geleegd');
        } else
            return redirect('cart')->withErrors('Er is een fout opgetreden tijden het legen van de winkelwagen');
    }

    /**
     * Mail the order to the company
     *
     * @return mixed
     */
    public function order()
    {
        if (Cart::count(false) !== 0) {
            if (Input::has('addressId')) {
                if (Input::get('addressId') === '-2') {
                    $address = new \stdClass();

                    $address->name = '';
                    $address->street = 'Wordt gehaald';
                    $address->postcode = '';
                    $address->city = '';
                    $address->telephone = '';
                    $address->mobile = '';

                } else if (Address::where('id', Input::get('addressId'))->where('User_id', Auth::user()->login)->first())
                    $address = Address::where('id', Input::get('addressId'))->where('User_id', Auth::user()->login)->first();
                else
                    return redirect('/cart')->withErrors('Het opgegeven adres hoort niet bij uw account');

                $data['address'] = $address;
                $data['cart'] = Cart::content();
                $data['comment'] = (Input::has('comment') ? Input::get('comment') : false);

                \Mail::send('email.order', $data, function ($message) {
                    $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');

                    if (Auth::user()->login === "99999")
                        $message->to('gfw@wiringa.nl');
                    else
                        $message->to('verkoop@wiringa.nl');

                    $message->subject('Webshop order');
                });

                $items = [];

                foreach (Cart::content() as $item) {
                    $items[] = [
                        'id' => $item->id,
                        'name' => $item->name,
                        'qty' => $item->qty
                    ];
                }

                $order = new Order();

                $order->products = serialize($items);
                $order->User_id = Auth::user()->login;
                $order->comment = $data['comment'];
                $order->addressId = Input::get('addressId');

                $order->save();

                Session::flash('order', true);

                Cart::destroy();

                $user = User::find(Auth::user()->id);
                $user->cart = "a:0:{}";
                $user->save();

                return redirect('/cart/order/finished');
            } else
                return redirect('/cart')->withErrors('Geen adres opgegeven');
        } else
            return redirect('/')->withErrors('Er zitten geen producten in uw winkelwagen!');
    }

}