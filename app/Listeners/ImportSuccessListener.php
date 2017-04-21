<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Models\Content;
use Luna\Importer\Events\ImportSuccess;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportSuccessListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ImportSuccess  $event
     * @return void
     */
    public function handle(ImportSuccess $event)
    {
        $runner = $event->runner;
        $importer = $event->importer;

        $message = __(':count :type zijn geimporteerd in :time seconden. <br />Geheugen gebruik: :memory<br />Nieuwe regels: :added<br />Aangepaste regels: :updated<br />Verwijderde regels: :deleted<br />Niet-aangepaste regels: :unchanged', [
            'count' => app('format')->number($runner->lines),
            'type' => __(str_plural($importer->getType(), $runner->lines)),
            'time' => Carbon::now()->diffInSeconds($runner->now),
            'memory' => app('format')->bytes(memory_get_peak_usage()),
            'added' => $runner->added,
            'updated' => $runner->updated,
            'deleted' => $runner->deleted,
            'unchanged' => $runner->unchanged
        ]);

        Content::where('name', 'admin.' . $importer->getType() . '_import')->update([
            'content'    => $message,
            'updated_at' => Carbon::now('Europe/Amsterdam'),
            'error'      => false,
        ]);
    }
}
