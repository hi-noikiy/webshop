<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Models\Content;
use Luna\Importer\Events\ImportFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportFailedListener
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
     * @param  ImportFailed  $event
     * @return void
     */
    public function handle(ImportFailed $event)
    {
        $runner = $event->runner;
        $importer = $event->importer;
        $exception = $event->exception;

        $message = __('An error has occurred during the :type import, the database has not been altered. The error message is: :error.', [
            'type' => $importer->getType(),
            'error' => $exception->getMessage()
        ]);

        Content::where('name', 'admin.' . $importer->getType() . '_import')->update([
            'content'    => $message,
            'updated_at' => Carbon::now('Europe/Amsterdam'),
            'error'      => true,
        ]);

        \Mail::send('email.import_error_notice', [
            'error' => $message,
            'type' => $importer->getType()
        ], function ($message) use ($importer) {
            $message->subject('[' . config('app.name') . '] ' . ucfirst($importer->getType()) . ' import error');
        });
    }
}
