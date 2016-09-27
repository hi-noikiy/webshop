<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Content;
use App\Description;
use App\Http\Controllers\Controller;
use App\Order;
use App\Product;
use App\User;
use DB;
use Illuminate\Http\Request;
use Response;
use Spatie\Analytics\Period;

/**
 * Class ApiController
 * @package App\Http\Controllers\Admin
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ApiController extends Controller
{
    /**
     * Return the CPU load.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cpu(Request $request)
    {
        $uptime = exec('uptime');

        $load = array_slice(explode(' ', str_replace(',', '', $uptime)), -3);
        $max = exec('grep "model name" /proc/cpuinfo | wc -l');

        $data = [
            'load' => $load[0],
            'max'  => $max,
        ];

        return Response::json($data);
    }

    /**
     * Return the RAM usage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ram(Request $request)
    {
        $total = preg_replace("/\D/", '', exec("grep 'MemTotal' /proc/meminfo"));
        $free = preg_replace("/\D/", '', exec("grep 'MemFree' /proc/meminfo"));

        $freePercentage = exec("free -t | grep 'buffers/cache' | awk '{print $4/($3+$4) * 100}'");

        $data = [
            'total'          => $total,
            'freePercentage' => $freePercentage,
            'free'           => $free,
        ];

        return Response::json($data);
    }

    /**
     * Get data for a chart.js chart.
     *
     * @param  string  $type
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chart(Request $request, string $type)
    {
        // Get the count, year and month
        if ($type === 'orders') {
            $groupedOrders = Order::select(DB::raw("COUNT(id) as 'count', YEAR(created_at) as 'year', MONTH(created_at) as 'month'"))
                ->where(DB::raw('YEAR(created_at)'), $request->input('year'))
                ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                ->get();

            return Response::json([
                'message' => "Chart data for chart '{$type}'",
                'payload' => $groupedOrders,
            ]);
        } elseif ($type === 'browsers') {
            $days = $request->input('days');

            return Response::json([
                'message' => "Chart data for chart '{$type}'",
                'payload' => \Analytics::fetchTopBrowsers(Period::days($days)),
            ]);
        } else {
            return Response::json([
                'message' => 'Unknown chart type',
            ], 400);
        }
    }

    /**
     * Return a single product.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function product(Request $request, $id)
    {
        $product = Product::where('number', $id)->first();

        if ($product) {
            return Response::json([
                'status'    => 'success',
                'payload'   => $product,
                'message'   => "Details for product: {$id}",
            ]);
        } else {
            return Response::json([
                'status'    => 'failure',
                'message'   => "No details for product: {$id}",
            ], 404);
        }
    }

    /**
     * Get some user details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function companyDetails(Request $request)
    {
        if ($request->has('id')) {
            $company = Company::with('mainUser')->where('login', $request->input('id'))->first();

            if ($company !== null) {
                return Response::json([
                    'message' => 'User details for user '.$company->login,
                    'payload' => $company,
                ]);
            } else {
                return Response::json([
                    'message' => 'No user found with login '.$request->input('id'),
                ], 404);
            }
        } else {
            return Response::json([
                'message' => 'Missing request parameter: `id`',
            ], 400);
        }
    }

    /**
     * Get the content that belongs to the page/field.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function content(Request $request)
    {
        if ($request->has('page')) {
            $data = Content::where('name', $request->input('page'))->first();

            if ($data) {
                return Response::json([
                    'message' => 'Content for page '.$request->input('page'),
                    'payload' => $data,
                ]);
            } else {
                return Response::json([
                    'message' => 'No content found for page: '.$request->input('page'),
                ], 404);
            }
        } else {
            return Response::json([
                'message' => 'Missing request parameter: `page`',
            ], 400);
        }
    }

    /**
     * Get the description of a product
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function description(Request $request)
    {
        if ($request->has('product')) {
            $product = Product::findByNumber($request->input('product'));

            if ($product->description) {
                return Response::json([
                    'message' => 'Description for product '.$request->input('product'),
                    'payload' => $product->description,
                ]);
            } else {
                return Response::json([
                    'message' => 'No description found for page: '.$request->input('product'),
                ], 404);
            }
        } else {
            return Response::json([
                'message' => 'Missing request parameter: `product`',
            ], 400);
        }
    }
}
