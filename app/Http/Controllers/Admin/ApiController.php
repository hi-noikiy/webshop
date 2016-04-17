<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use App\Order;
use Illuminate\Http\Request;

use Response, DB;

/**
 * Class ApiController
 * @package App\Http\Controllers\Admin
 */
class ApiController extends Controller {

    /**
     * Return the CPU load
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function cpu(Request $request)
    {
        if ($request->ajax()) {
            $uptime = exec('uptime');

            $load = array_slice(explode(' ', str_replace(',', '', $uptime)), -3);
            $max = exec('grep "model name" /proc/cpuinfo | wc -l');

            $data = [
                'load' => $load[0],
                'max' => $max,
            ];

            return Response::json($data);
        } else
            return redirect()->back();
    }

    /**
     * Return the RAM usage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function ram(Request $request)
    {
        if ($request->ajax()) {
            $total = preg_replace("/\D/", "", exec("grep 'MemTotal' /proc/meminfo"));
            $free = preg_replace("/\D/", "", exec("grep 'MemFree' /proc/meminfo"));

            $freePercentage = exec("free -t | grep 'buffers/cache' | awk '{print $4/($3+$4) * 100}'");

            $data = [
                'total' => $total,
                'freePercentage' => $freePercentage,
                'free' => $free
            ];

            return Response::json($data);
        } else
            return redirect()->back();
    }

    /**
     * Get data for a chart.js chart
     *
     * @param Request $request
     * @param string $type
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function chart(Request $request, string $type)
    {
        if ($request->ajax()) {
            if ($type === 'orders')
            { // Get the count, year and month
                $groupedOrders = Order::select(DB::raw("COUNT(id) as 'count', YEAR(created_at) as 'year', MONTH(created_at) as 'month'"))
                    ->where(DB::raw('YEAR(created_at)'), request()->input('year'))
                    ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                    ->get();

                return Response::json($groupedOrders);
            } else
                return Response::json(['Unknown chart type'], 400);
        } else
            return redirect()->back();
    }

    /**
     * Return a single product
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function product(Request $request, $id)
    {
        if ($request->ajax()) {
            $product = Product::where('number', $id)->first();

            if ($product) {
                return Response::json([
                    'status'    => 'success',
                    'payload'   => $product,
                    'message'   => "Details for product: {$product}"
                ]);
            } else {
                return Response::json([
                    'status'    => 'failure',
                    'message'   => "No details for product: {$product}"
                ]);
            }
        } else
            return redirect()->back();
    }

}