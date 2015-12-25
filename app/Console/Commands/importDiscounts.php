<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use File, Validator, DB, Helper;
use App\Discount;
use App\Content;

class importDiscounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:discounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read the discounts file and import the new discounts';

    /**
     * This will hold the path to the discounts csv file
     *
     * @var string
     */
    private $filePath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->filePath = storage_path() . "/import/discounts.csv";
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (File::exists($this->filePath))
        {
            ini_set('memory_limit', '1G');

            $validator = Validator::make(
                ['fileType' => finfo_file(finfo_open(FILEINFO_MIME_TYPE), $this->filePath)],
                ['fileType' => 'required|string:text/plain|string:text/csv']
            );

            if ($validator->fails())
                    $this->error("Invalid CSV file!");
            else
            {
                $startTime = microtime(true);
                $fh        = fopen($this->filePath, 'r');
                $lineCount = count(file($this->filePath));
                $bar       = $this->output->createProgressBar($lineCount);

                $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %memory:6s%');

                DB::beginTransaction();

                try {
                    // Truncate the products table
                    (new Discount)->newQuery()->delete();

                    $line = 0;

                    while(!feof($fh))
                    {
                        $data = str_getcsv(fgets($fh), ';');

                        // Make sure column count is 24 at minimum
                        if (count($data) === 8) {

                            DB::table('discounts')->insert([
                                    'table'         => $data[0],
                                    'User_id'       => ($data[1] !== "" ? $data[1] : 0),
                                    'product'       => (is_numeric($data[2]) ? $data[2] : 0),
                                    'start_date'    => $data[3],
                                    'end_date'      => $data[4],
                                    'discount'      => $data[5],
                                    'group_desc'    => $data[6],
                                    'product_desc'  => $data[7],
                            ]);

                            $line++;

                            $bar->advance();
                        }
                    }
                    fclose($fh);

                    $bar->finish();

                    $this->line("\r\n");

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();

                    $errorMessage = "Er is een fout opgetreden, de database is niet aangepast: <br />" . $e->getMessage();

                    unlink($this->filePath);
                    $this->error($errorMessage);

                    Content::where('name', 'admin.discount_import')->update([
                        'content' => $errorMessage,
                        'updated_at' => Carbon::now('Europe/Amsterdam'),
                        'error' => true
                    ]);

                    \Mail::send('email.import_error_notice', ['error' => $errorMessage, 'type' => 'korting'], function($message)
        			{
                        $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');

        				$message->to('gfw@wiringa.nl');

        				$message->subject('[WTG Webshop] Korting import error');
        			});

                    exit();
                }

                $endTime = round(microtime(true) - $startTime, 4);

                $this->info($line . " discounts were imported in " . $endTime . " seconds using " . number_format(memory_get_peak_usage() / 1000000, 2) . " MBs of memory");

                Content::where('name', 'admin.discount_import')->update([
                    'content' => $line . " kortingen zijn geimporteerd. <br />Geheugen gebruik: " . number_format(memory_get_peak_usage() / 1000000, 2) . " MB",
                    'updated_at' => Carbon::now('Europe/Amsterdam'),
                    'error' => false
                ]);
            }

            // Always remove the file at the end of the import to make sure that it doesn't get parsed twice
            unlink($this->filePath);
            exit();
        }
    }
}
