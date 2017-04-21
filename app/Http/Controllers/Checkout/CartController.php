<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use WTG\Checkout\Interfaces\QuoteInterface;
use WTG\Catalog\Interfaces\ProductInterface;
use WTG\Checkout\Requests\AddProductRequest;
use WTG\Checkout\Requests\EditProductRequest;
use WTG\Checkout\Interfaces\QuoteItemInterface;
use WTG\Checkout\Requests\UpdateProductRequest;

/**
 * Cart controller.
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CartController extends Controller
{
    /**
     * @var QuoteInterface
     */
    protected $quote;

    /**
     * Show the cart.
     *
     * @return \Illuminate\View\View
     */
    public function view()
    {
        $customer = \Auth::user();
        $quote_items = app()->make(QuoteInterface::class)
            ->findQuoteByCustomerId($customer->getId(), $customer->getCompanyId())->getItems();

        $this->recalculatePrices();

        return view('checkout.cart', compact('quote_items'));
    }

    /**
     * Add a product to the cart.
     *
     * @param  AddProductRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(AddProductRequest $request)
    {
        $productId = $request->input('product');
        $quantity = (int) $request->input('quantity');
        $quote = $this->getActiveQuote();
        /** @var ProductInterface $product */
        $product = app()->make(ProductInterface::class)
            ->find($productId);

        if ($product === null) {
            return response([
                'success' => false,
                'message' => trans('checkout::cart.product_not_found', ['product' => $productId]),
                'count' => $quote->getItemCount()
            ]);
        }

        $added = $quote->addProduct(
            $product->getId(),
            $quantity,
            $product->getSku(),
            $product->getName(),
            $product->getPrice(true),
            $product->getPrice(true, $quantity)
        );

        if ($added) {
            return response([
                'success' => true,
                'message' => trans('checkout::cart.item_add_success'),
                'count' => $quote->getItemCount()
            ]);
        }

        return response([
            'success' => false,
            'message' => trans('checkout::cart.item_add_error')
        ], 400);
    }

    /**
     * Remove an item from the cart.
     *
     * @param  string  $itemId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(string $itemId)
    {
        $quote = $this->getActiveQuote();
        /** @var QuoteItemInterface $quoteItem */
        $quoteItem = $quote->getItems()->first(function ($item) use ($itemId) {
            return $item->getId() === $itemId;
        });

        if ($quoteItem->delete()) {
            return response([
                'success' => true,
                'message' => trans('checkout::cart.item_remove_success'),
                'count' => $quote->getItemCount()
            ]);
        }

        return response([
            'success' => false,
            'message' => trans('checkout::cart.item_remove_error')
        ], 400);
    }

    /**
     * Update a single item.
     *
     * @param  UpdateProductRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $quote = $this->getActiveQuote();
        $qty = (int) $request->input('quantity');
        /** @var QuoteItemInterface $quoteItem */
        $quoteItem = $quote->getItems()->first(function ($item) use ($id) {
            return $item->getId() === $id;
        });

        if ($quoteItem === null) {
            return response([
                'success' => false,
                'message' => 'Onbekend id opgegeven'
            ], 400);
        }

        /** @var ProductInterface $product */
        $product = app()->make(ProductInterface::class)
            ->find($quoteItem->getProductId());

        if ($product === null) {
            $quoteItem->delete();

            return response([
                'success' => false,
                'message' => 'Het product is verwijderd uit uw winkelmandje omdat het product niet (meer) in ons assortiment zit.'
            ], 400);
        }

        $quoteItem->setQuantity($qty);
        $quoteItem->setPrice($product->getPrice(true));
        $quoteItem->setSubtotal($product->getPrice(true, $qty));
        $quoteItem->save();

        return response([
            'success' => true,
            'subtotal' => app('format')->price(
                $product->getPrice(true, $quoteItem->getQuantity())
            ),
            'total' => app('format')->price($quote->getGrandTotal(true)),
            'quantity' => (int) $qty
        ]);
    }

    /**
     * Destroy the cart.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        $quote = $this->getActiveQuote();
        $quote->getItems()->each(function ($item) {
            $item->delete();
        });

        if ($quote->delete()) {
            return response([
                'success' => true,
                'message' => trans('checkout::cart.destroy_success')
            ]);
        }

        return response([
            'success' => false,
            'message' => trans('checkout::cart.destroy_error')
        ], 400);
    }

    /**
     * Get the active quote.
     *
     * @return \WTG\Checkout\Interfaces\QuoteInterface
     */
    protected function getActiveQuote()
    {
        if ($this->quote !== null) {
            return $this->quote;
        }

        $this->quote = app()->make(QuoteInterface::class)
            ->findQuoteByCustomerId(\Auth::id(), \Auth::user()->getCompanyId());

        return $this->quote;
    }

    /**
     * Recalculate the quote item prices
     *
     * @return void
     */
    protected function recalculatePrices()
    {
        $quote = $this->getActiveQuote();

        $quote->getItems()->each(function ($item) {
            /** @var QuoteItemInterface $item */
            /** @var ProductInterface $product */
            $product = app()->make(ProductInterface::class)->find($item->getProductId());

            if ($product === null) {
                $item->delete();

                return;
            }

            $item->setPrice($product->getPrice(true));
            $item->setSubtotal($product->getPrice(true, $item->getQuantity()));
            $item->save();
        });
    }
}
