<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use App\Order;
use App\User;
use App\Content;
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
        $uptime = exec('uptime');

        $load = array_slice(explode(' ', str_replace(',', '', $uptime)), -3);
        $max = exec('grep "model name" /proc/cpuinfo | wc -l');

        $data = [
            'load' => $load[0],
            'max' => $max,
        ];

        return Response::json($data);
    }

    /**
     * Return the RAM usage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function ram(Request $request)
    {
        $total = preg_replace("/\D/", "", exec("grep 'MemTotal' /proc/meminfo"));
        $free = preg_replace("/\D/", "", exec("grep 'MemFree' /proc/meminfo"));

        $freePercentage = exec("free -t | grep 'buffers/cache' | awk '{print $4/($3+$4) * 100}'");

        $data = [
            'total' => $total,
            'freePercentage' => $freePercentage,
            'free' => $free
        ];

        return Response::json($data);
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
        if ($type === 'orders')
        { // Get the count, year and month
            $groupedOrders = Order::select(DB::raw("COUNT(id) as 'count', YEAR(created_at) as 'year', MONTH(created_at) as 'month'"))
                ->where(DB::raw('YEAR(created_at)'), $request->input('year'))
                ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                ->get();

            return Response::json($groupedOrders);
        } else
            return Response::json(['Unknown chart type'], 400);
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
        $product = Product::where('number', $id)->first();

        if ($product) {
            return Response::json([
                'status'    => 'success',
                'payload'   => $product,
                'message'   => "Details for product: {$id}"
            ]);
        } else {
            return Response::json([
                'status'    => 'failure',
                'message'   => "No details for product: {$id}"
            ]);
        }
    }

    /**
     * Get some user details
     *
     * @param Request $request
     * @return mixed
     */
    public function userDetails(Request $request)
    {
        if ($request->has('id')) {
            $user = User::where('login', $request->input('id'))->first();

            if ($user !== null) {
                return Response::json([
                    'message' => 'User details for user ' . $user->login,
                    'payload' => $user
                ]);
            } else {
                return Response::json([
                    'message' => 'No user found with login ' . $request->input('id'),
                ], 404);
            }
        } else {
            return Response::json([
                'message' => 'Missing request parameter: `id`',
            ], 400);
        }
    }

    /**
     * Get the content that belongs to the page/field
     *
     * @param Request $request
     * @return mixed
     */
    public function content(Request $request)
    {
        if ($request->has('page')) {
            $data = Content::where('name', $request->input('page'))->first();

            if ($data) {
                return Response::json([
                    'message' => 'Content for page ' . $request->input('page'),
                    'payload' => $data
                ]);
            } else {
                return Response::json([
                    'message' => 'No content found for page: ' . $request->input('page'),
                ], 404);
            }

        } else
            return Response::json([
                'message' => 'Missing request parameter: `page`',
            ], 400);
    }
}
