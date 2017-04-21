<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Content;
use Illuminate\Http\Request;
use WTG\Block\Models\Block;

/**
 * Class DashboardController.
 *
 * @author Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DashboardController extends Controller
{
    /**
     * The dashboard view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view()
    {
        $years = request('years', null);
        if (is_null($years) || is_array($years)) {
            $product_import = Block::getByTag('admin.product_import');
            $discount_import = Block::getByTag('admin.discount_import');
            $orderData = $this->getOrderData($years);
            $orders = $orderData->get('orders');
            $averagePerMonth = $orderData->get('average');

            return view('admin.dashboard.index', compact('product_import', 'discount_import', 'orders', 'averagePerMonth'));
        } else {
            return redirect(route('admin.dashboard'));
        }
    }

    /**
     * Get the server stats.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        $cpuData = $this->cpu();
        $ramData = $this->ram();
        $diskData = $this->disk();

        return response()->json([
            'cpu' => $cpuData,
            'ram' => $ramData,
            'disk' => $diskData,
        ]);
    }

    /**
     * Return the disk usage.
     *
     * @return array
     */
    protected function disk()
    {
        return [
            'total' => disk_total_space('/'),
            'free' => disk_free_space('/'),
        ];
    }

    /**
     * Return the CPU load.
     *
     * @return array
     */
    protected function cpu()
    {
        $uptime = exec('uptime');

        $load = array_slice(explode(' ', str_replace(',', '', $uptime)), -3);
        $max = exec('grep "model name" /proc/cpuinfo | wc -l');

        return [
            'load' => $load[0],
            'max'  => $max,
        ];
    }

    /**
     * Return the RAM usage.
     *
     * @return array
     */
    protected function ram()
    {
        $total = preg_replace("/\D/", '', exec("grep 'MemTotal' /proc/meminfo"));
        $free = preg_replace("/\D/", '', exec("grep 'MemFree' /proc/meminfo"));

        $freePercentage = exec("free -t | grep 'buffers/cache' | awk '{print $4/($3+$4) * 100}'");

        return [
            'total'          => $total,
            'freePercentage' => $freePercentage,
            'free'           => $free,
        ];
    }

    /**
     * Get the order data
     *
     * @param  array|null  $years
     * @return \Illuminate\Support\Collection
     */
    protected function getOrderData(array $years = null)
    {
        $averagePerMonth = [0,0,0,0,0,0,0,0,0,0,0,0];

        $orders = Order::get()
            ->groupBy(function ($item, $key) {
                return $item->created_at->format('Y');
            })
            ->map(function ($item, $key) use (&$averagePerMonth, $years) {
                if ($years !== null) {
                    if (!in_array($key, $years)) {
                        return;
                    }
                }

                return $item->groupBy(function ($item, $key) {
                    return $item->created_at->format('n');
                })->map(function ($item, $key) use (&$averagePerMonth) {
                    $averagePerMonth[$key-1] += $item->count();

                    return $item->count();
                });
            });

        $averagePerMonth = collect($averagePerMonth)->map(function ($item, $key) use ($orders) {
            $count = $orders->filter(function ($item) {
                return $item !== null;
            })->keys()->count();

            if ($count === 0) {
                return 0;
            }

            return $item / $count;
        });

        return collect([
            'average' => $averagePerMonth,
            'orders' => $orders
        ]);
    }
}
