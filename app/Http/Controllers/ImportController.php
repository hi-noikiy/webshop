<?php namespace App\Http\Controllers;

use App\Product;
use DB, Input, Validator, Redirect;

class ImportController extends Controller {

    /**
    * Product import handler
    *
    * @return mixed
    */
    public function product()
    {
            if (Input::hasFile('productFile'))
            {
                    ini_set('memory_limit', '1G');

                    $file = Input::file('productFile');

                    $validator = Validator::make(
                            ['fileType' => $file->getMimeType()],
                            ['fileType' => 'required|string:text/plain|string:text/csv']
                    );

                    if ($validator->fails())
                            return Redirect::back()->withErrors( $validator->errors());
                    else
                    {
                            \Debugbar::disable();
                            header('Content-Encoding: identity');
                            header('X-Accel-Buffering: no');
                            // This loop is used to send the first 4096 bytes for the output buffering to work
                            echo "<!--";
                            for ($i=0; $i < 4089; $i++) {
                                    echo "X";
                            }
                            echo "-->";

                            echo "<h1>Product import</h1>";
                            echo "Preparing database transaction.... <br />";
                            echo "[" . "<div style=' position: absolute; top: 84px; left:  821px;'>]</div>";

                            $startTime = microtime(true);

                            $fh        = fopen($file->getRealPath(), 'r');
                            $lineCount = count(file($file->getRealPath()));
                            $products_with_related_products = [];

                            DB::beginTransaction();

                            try {
                                    // Truncate the products table
                                    (new Product)->newQuery()->delete();

                                    DB::connection()->disableQueryLog();

                                    $line = $lastPercent = 0;

                                    while(!feof($fh))
                                    {
                                        $data = str_getcsv(fgets($fh), ';');

                                        // Make sure column count is 24 at minimum
                                        if (count($data) === 30) {

                                            DB::table('products')->insert([
                                                    'name'             => $data[0],
                                                    'number'           => $data[3],
                                                    'group'            => $data[4],
                                                    'altNumber'        => $data[5],
                                                    'stockCode'        => $data[7],
                                                    'registered_per'   => $data[8],
                                                    'packed_per'       => $data[9],
                                                    'price_per'        => $data[10],
                                                    'refactor'         => preg_replace("/\,/", ".", $data[12]),
                                                    'supplier'         => $data[13],
                                                    'ean'              => $data[14],
                                                    'image'            => $data[15],
                                                    'length'           => $data[17],
                                                    'price'            => $data[18],
                                                    'vat'              => $data[20],
                                                    'brand'            => $data[21],
                                                    'series'           => $data[22],
                                                    'type'             => $data[23],
                                                    'special_price'    => ($data[24] === "" ? "0.00" : preg_replace("/\,/", ".", $data[24])),
                                                    'action_type'      => $data[25],
                                                    'keywords'         => $data[26],
                                                    'related_products' => $data[27],
                                                    'catalog_group'    => $data[28],
                                                    'catalog_index'    => $data[29],
                                            ]);

                                            $line++;
                                            $percentage  = round(($line / $lineCount) * 100);

                                            if ($percentage !== $lastPercent)
                                            {
                                                    echo "#";
                                                    echo "<div style='position: absolute; top: 105px; left: 424px;width: 30px;background:white;'>$percentage%</div>";
                                                    ob_flush();
                                                    flush();
                                            }

                                            $lastPercent = $percentage;

                                            // Save the products with related products in an array for verification
                                            if ($data[27] != "")
                                            {
                                                $products_with_related_products[ $data[3] ] = $data[27];
                                            }
                                        }
                                    }
                                    fclose($fh);

                                    echo "<br /><br />";

                                    ob_flush();
                                    flush();

                                    sleep(1);

                                    echo "Checking related products...<br />";

                                    ob_flush();
                                    flush();

                                    self::checkRelatedProducts($products_with_related_products);

                                    sleep(1);

                                    echo "Committing data...<br />";

                                    ob_flush();
                                    flush();

                                    DB::commit();
                            } catch (\Exception $e) {
                                    echo "<br /><br />";
                                    echo "An error has occurred, the database will be rolled back to it's previous state...<br />";

                                    ob_flush();
                                    flush();

                                    DB::rollback();

                                    return Redirect::back()->withErrors("Er is een fout opgetreden, de database is niet aangepast: " . $e->getMessage());
                            }

                            sleep(1);

                            $endTime = round(microtime(true) - $startTime, 4);

                            return Redirect::intended('admin/importsuccess')->with([
                                'count' => $line,
                                'type'  => 'product',
                                'time'  => $endTime,
                                'memory' => memory_get_peak_usage()
                            ]);
                    }
            } else
                    return Redirect::back()->withErrors( 'Geen bestand geselecteerd');
    }

    /**
    * This function will handle the discount import
    *
    * @return mixed
    */
    public function discount()
    {
            if (Input::hasFile('discountFile'))
            {
                    ini_set('memory_limit', '1G');

                    $file = Input::file('discountFile');

                    $validator = Validator::make(
                            ['fileType' => $file->getMimeType()],
                            ['fileType' => 'required|string:text/plain|string:text/csv']
                    );

                    if ($validator->fails())
                            return Redirect::back()->withErrors( $validator->errors());
                    else
                    {
                            header('Content-Encoding: identity');
                            header('X-Accel-Buffering: no');
                            \Debugbar::disable();
                            // This loop is used to send the first 4096 bytes for the output buffering to work
                            echo "<!--";
                            for ($i=0; $i < 4089; $i++) {
                                    echo "X";
                            }
                            echo "-->";

                            echo "<h1>Korting import</h1>";
                            echo "Preparing database transaction.... <br />";
                            echo "[" . "<div style=' position: absolute; top: 84px; left:  821px;'>]</div>";

                            $startTime = microtime(true);

                            $fh = fopen($file->getRealPath(), 'r');
                            $lineCount = count(file($file->getRealPath()));

                            DB::beginTransaction();

                            try {
                                    // Truncate the discount table
                                    (new Discount)->newQuery()->delete();

                                    DB::connection()->disableQueryLog();

                                    $line = $lastPercent = 0;

                                    while(!feof($fh))
                                    {
                                        $data = str_getcsv(fgets($fh), ';');

                                        // Make sure the column count is right
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

                                            $percentage  = round(($line / $lineCount) * 100);

                                            if ($percentage !== $lastPercent)
                                            {
                                                    echo "#";
                                                    echo "<div style='position: absolute; top: 105px; left: 424px;width: 30px;background:white;'>$percentage%</div>";
                                                    ob_flush();
                                                    flush();
                                            }

                                            $lastPercent = $percentage;
                                        }
                                    }
                                    fclose($fh);

                                    echo "<br /><br />";

                                    ob_flush();
                                    flush();

                                    sleep(1);

                                    echo "Committing data...<br />";

                                    ob_flush();
                                    flush();

                                    DB::commit();
                            } catch (\Exception $e) {
                                    echo "<br /><br />";
                                    echo "An error has occurred, the database will be rolled back to it's previous state...<br />";

                                    ob_flush();
                                    flush();

                                    DB::rollback();

                                    return Redirect::back()->withErrors("Er is een fout opgetreden, de database is niet aangepast: " . $e->getMessage());
                            }

                            sleep(1);

                            $endTime = round(microtime(true) - $startTime, 4);

                            return Redirect::to('admin/importsuccess')->with([
                                'count' => $line,
                                'time'  => $endTime,
                                'type'  => 'korting',
                                'memory' => memory_get_peak_usage()
                            ]);
                    }
            } else
                    return Redirect::back()->withErrors( 'Geen bestand geselecteerd');
    }

    /**
    * This function will handle the image import
    *
    * @return mixed
    */
    public function image()
    {
            if (Input::hasFile('imageFile') && Input::file('imageFile')->isValid())
            {
                    $file      = Input::file('imageFile');
                    $fileName  = $file->getClientOriginalName();
                    $fileMime  = $file->getMimeType();
                    $startTime = microtime(true);

                    $validator = Validator::make(
                            ['fileType' => $file],
                            ['fileType' => 'required|mimes:zip,jpg,png,gif,jpeg']
                    );

                    if ($validator->fails())
                            return Redirect::back()->withErrors( 'Geen geldig bestand geuploaded. Het bestand mag een afbeeling of Zip bestand zijn');
                    else
                    {
                            if ($fileMime === "application/zip")
                            {
                                    // Unzip the files to the product image folder
                                    \Zipper::make($file->getRealPath())->extractTo(public_path() . "/img/products");

                                    // This is used to count the number of files in the zip
                                    $zip   = new \ZipArchive;
                                    $zip->open($file->getRealPath());
                                    $count = $zip->numFiles;
                            } else
                            {
                                    // If it's an image file, move it directly to the product image folder
                                    $file->move(public_path() . "/img/products", $fileName);

                                    $count = 1;
                            }

                            $endTime = round(microtime(true) - $startTime, 4);

                            return Redirect::to('/admin/importsuccess')->with(['count' => $count, 'time' => $endTime, 'type' => 'afbeelding']);
                    }
            } else
                    return Redirect::back()->withErrors('Geen bestand geselecteerd of de afbeelding is ongeldig');
    }

    /**
    * This function will handle the image import
    *
    * @return mixed
    */
    public function download()
    {
            if (Input::hasFile('imageFile') && Input::file('imageFile')->isValid())
            {
                    $file      = Input::file('imageFile');
                    $fileName  = $file->getClientOriginalName();
                    $fileMime  = $file->getMimeType();
                    $startTime = microtime(true);

                    $validator = Validator::make(
                            ['fileType' => $file],
                            ['fileType' => 'required|mimes:zip,pdf']
                    );

                    if ($validator->fails())
                            return Redirect::back()->withErrors( 'Geen geldig bestand geuploaded. Het bestand mag een Zip of PDF bestand zijn');
                    else
                    {

                            // If it's an image file, move it directly to the product image folder
                            $file->move(public_path() . "/dl", $fileName);

                            $count = 1;

                            $endTime = round(microtime(true) - $startTime, 4);

                            return Redirect::to('/admin/importsuccess')->with(['count' => $count, 'time' => $endTime, 'type' => 'download']);
                    }
            } else
                    return Redirect::back()->withErrors('Geen bestand geselecteerd of het bestand is ongeldig');
    }

    /**
     * Check if all the related products exist
     *
     * @return void
     */
    private function checkRelatedProducts($products_with_related_products)
    {
        foreach ($products_with_related_products as $product => $relatedString) {

            foreach(explode(",", $relatedString) as $relatedProduct) {
                if (Product::where('number', $relatedProduct)->count() === 0) {
                    throw new \Exception("Product " . $product . " heeft een niet-bestaand gerelateerd product: " . $relatedProduct);
                }
            }

        }
    }

}
