<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use Helper, Redirect;

class CacheController extends Controller
{

    /**
     * View cache information
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function stats()
    {
        $opcache = opcache_get_status();

        $opcache_stats    = collect($opcache['opcache_statistics']);

        $free_memory    = $opcache['memory_usage']['free_memory'];
        $used_memory    = $opcache['memory_usage']['used_memory'];
        $wasted_memory  = $opcache['memory_usage']['wasted_memory'];

        $total_memory   = $free_memory + $used_memory + $wasted_memory;

        /**
         * Calculate opcache memory in MB
         */
        $opcache_memory = collect([
            'total'  => Helper::convertByte($total_memory),
            'free'   => Helper::convertByte($free_memory),
            'used'   => Helper::convertByte($used_memory),
            'wasted' => Helper::convertByte($wasted_memory),
        ]);

        $cache_hits     = $opcache_stats->get('hits');
        $cache_misses   = $opcache_stats->get('misses');

        $opcache_hitrate = collect([
            'hitrate'   => round(($cache_hits / ($cache_hits + $cache_misses)) * 100, 1),
            'hits'      => $cache_hits,
            'misses'    => $cache_misses
        ]);

        return view('admin.cache', [
            'opcache_enabled'       => $opcache['opcache_enabled'],
            'opcache_full'          => $opcache['cache_full'],
            'opcache_stats'         => $opcache_stats,
            'opcache_memory'        => $opcache_memory,
            'opcache_hitrate'       => $opcache_hitrate
        ]);
    }

    /**
     * Reset the cache
     *
     * @return mixed
     */
    public function reset()
    {
        if (function_exists('opcache_reset')) {
            if (opcache_reset()) {
                return redirect()
                    ->back()
                    ->with('status', 'De cache is gereset.');
            } else {
                return redirect()
                    ->back()
                    ->withErrors('Er is een fout opgetreden tijdens het resetten van de cache. Mogelijk staat de module \'OpCache\' uit.');
            }
        } else {
            return redirect()
                ->back()
                ->withErrors('Er is een fout opgetreden tijdens het resetten van de cache. De \'OpCache module is niet geinstalleerd.\'');
        }
    }

}