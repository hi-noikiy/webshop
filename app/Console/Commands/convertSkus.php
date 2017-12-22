<?php

namespace App\Console\Commands;

use App\Description;
use App\Discount;
use App\Order;
use App\Pack;
use App\PackProduct;
use App\Product;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class convertSkus extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'convert:skus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert the skus to the new format.';

    /**
     * @var array
     */
    protected $productMap = [];

    /**
     * @var array
     */
    protected $notFound = [];

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Exception|\Throwable
     */
    public function handle()
    {
        Model::unguard();

        $this->createMap();

        \DB::transaction(function () {
            $this->convertProducts();

            $this->convertDiscounts();

            $this->convertFavoritesAndCarts();

            $this->convertDescriptions();

            $this->convertOrders();

            $this->convertPacks();

            $this->convertPackProducts();

            $this->clearSessions();
        });

        Model::reguard();

        $this->output->success('Job\'s done!');
    }

    /**
     * Remove sessions to prevent issues with cart.
     *
     * @return void
     */
    public function clearSessions()
    {
        $this->output->text('Clearing sessions');

        \DB::table('sessions')->delete();
    }

    /**
     * Convert the discounts.
     *
     * @return void
     */
    protected function convertDiscounts()
    {
        $this->output->text('Converting discounts');
        $this->notFound = [];

        Discount::where('product_desc', '!=', '')->chunk(25, function (Collection $items) {
            $items->each(function (Discount $product) {
                $oldSku = $product->getAttribute('product');
                $newSku = $this->productMap[$oldSku] ?? false;

                if (! $newSku) {
                    $this->notFound[$oldSku] = $oldSku;

                    return;
                }

                $product->setAttribute('product', $newSku);
                $product->save();
            });
        });

        if ($this->notFound) {
            $this->output->warning('No new sku found for the following product(s): ' . join(', ', $this->notFound));
        }
    }

    /**
     * Convert the favorites and carts.
     *
     * @return void
     */
    protected function convertFavoritesAndCarts()
    {
        $this->output->text('Converting favorites and carts');
        $this->notFound = [];

        User::chunk(25, function (Collection $items) {
            $items->each(function (User $order) {
                $favorites = unserialize($order->getAttribute('favorites'));
                $cart = unserialize($order->getAttribute('cart'));

                if (! $favorites && ! $cart) {
                    return;
                }

                if ($favorites) {
                    foreach ($favorites as $index => $oldSku) {
                        $newSku = $this->productMap[$oldSku] ?? false;

                        if (! $newSku) {
                            $this->notFound[$oldSku] = $oldSku;

                            continue;
                        }

                        $favorites[$index] = $newSku;
                    }
                }

                if ($cart) {
                    foreach ($cart as $rowId => $item) {
                        $oldSku = $item['id'];
                        $newSku = $this->productMap[$oldSku] ?? false;

                        if (! $newSku) {
                            $this->notFound[$oldSku] = $oldSku;

                            continue;
                        }

                        $item['id'] = $newSku;
                        $cart[$rowId] = $item;
                    }
                }

                $order->setAttribute('favorites', serialize($favorites));
                $order->setAttribute('cart', serialize($cart));
                $order->save();
            });
        });

        if ($this->notFound) {
            $this->output->warning('No new sku found for the following product(s): ' . join(', ', $this->notFound));
        }
    }

    /**
     * Convert the products.
     *
     * @return void
     */
    protected function convertProducts()
    {
        $this->output->text('Converting products');
        $this->notFound = [];

        Product::chunk(25, function (Collection $items) {
            $items->each(function (Product $product) {
                $oldSku = $product->getAttribute('number');
                $newSku = $this->productMap[$oldSku] ?? false;

                if (! $newSku) {
                    $this->notFound[$oldSku] = $oldSku;

                    return;
                }

                $product->setAttribute('number', $newSku);
                $product->save();
            });
        });

        if ($this->notFound) {
            $this->output->warning('No new sku found for the following product(s): ' . join(', ', $this->notFound));
        }
    }

    /**
     * Convert the packs.
     *
     * @return void
     */
    protected function convertPackProducts()
    {
        $this->output->text('Converting pack_products');
        $this->notFound = [];

        PackProduct::chunk(25, function (Collection $items) {
            $items->each(function (PackProduct $packProduct) {
                $oldSku = $packProduct->getAttribute('product');
                $newSku = $this->productMap[$oldSku] ?? false;

                if (! $newSku) {
                    $this->notFound[$oldSku] = $oldSku;

                    return;
                }

                $packProduct->setAttribute('product', $newSku);
                $packProduct->save();
            });
        });

        if ($this->notFound) {
            $this->output->warning('No new sku found for the following product(s): ' . join(', ', $this->notFound));
        }
    }

    /**
     * Convert the packs.
     *
     * @return void
     */
    protected function convertPacks()
    {
        $this->output->text('Converting packs');
        $this->notFound = [];

        Pack::chunk(25, function (Collection $items) {
            $items->each(function (Pack $pack) {
                $oldSku = $pack->getAttribute('product_number');
                $newSku = $this->productMap[$oldSku] ?? false;

                if (! $newSku) {
                    $this->notFound[$oldSku] = $oldSku;

                    return;
                }

                $pack->setAttribute('product_number', $newSku);
                $pack->save();
            });
        });

        if ($this->notFound) {
            $this->output->warning('No new sku found for the following product(s): ' . join(', ', $this->notFound));
        }
    }

    /**
     * Convert the orders.
     *
     * @return void
     */
    protected function convertOrders()
    {
        $this->output->text('Converting orders');
        $this->notFound = [];

        Order::chunk(25, function (Collection $items) {
            $items->each(function (Order $order) {
                $products = unserialize($order->getAttribute('products'));

                foreach ($products as $rowId => $item) {
                    $oldSku = $item['id'];
                    $newSku = $this->productMap[$oldSku] ?? false;

                    if (! $newSku) {
                        $this->notFound[$oldSku] = $oldSku;

                        continue;
                    }

                    $item['id'] = $newSku;
                    $products[$rowId] = $item;
                }

                $order->setAttribute('products', serialize($products));
                $order->save();
            });
        });

        if ($this->notFound) {
            $this->output->warning('No new sku found for the following product(s): ' . join(', ', $this->notFound));
        }
    }

    /**
     * Convert the product descriptions table.
     *
     * @return void
     */
    protected function convertDescriptions()
    {
        $this->output->text('Converting descriptions');
        $this->notFound = [];

        Description::chunk(25, function (Collection $items) {
            $items->each(function (Description $item) {
                $oldSku = $item->getAttribute('product_id');
                $newSku = $this->productMap[$oldSku] ?? false;

                if (! $newSku) {
                    $this->notFound[$oldSku] = $oldSku;

                    return;
                }

                $item->setAttribute('product_id', $newSku);
                $item->save();
            });
        });

        if ($this->notFound) {
            $this->output->warning('No new sku found for the following product(s): ' . join(', ', $this->notFound));
        }
    }

    /**
     * Create a map for the skus.
     *
     * @return void
     */
    protected function createMap()
    {
        $this->output->text('Creating product map');

        $handle = fopen(storage_path('app/sku-conversion.csv'), 'r');

        while(($data = fgetcsv($handle, 0, ';')) !== false) {
            $oldSku = $data[0] ?? false;
            $newSku = $data[1] ?? false;

            if (!$oldSku || !$newSku) {
                continue;
            }

            $this->productMap[$oldSku] = (int) $newSku;
        }
    }
}
