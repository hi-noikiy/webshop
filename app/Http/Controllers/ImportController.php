<?php namespace App\Http\Controllers;

use App\Product;
use App\Content;
use DB, Input, Validator, Redirect, Helper;

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
                    $file = Input::file('productFile');

                    $file->move(storage_path() . "/import", "products.csv");

                    $runTime = new \DateTime("Europe/Amsterdam");
                    $runTime->setTimestamp(strtotime('+' . Helper::timeToNextCronJob() . " minutes"));

                    Content::where('name', 'admin.product_import')->update(['content' => 'Er is een import ingepland voor ' . $runTime->format('H:i')]);

                    return Redirect::to('admin/importsuccess')->with(['type' => 'product']);
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
                $file = Input::file('discountFile');

                $file->move(storage_path() . "/import", "discounts.csv");

                $runTime = new \DateTime("Europe/Amsterdam");
                $runTime->setTimestamp(strtotime('+' . Helper::timeToNextCronJob() . " minutes"));

                Content::where('name', 'admin.discount_import')->update(['content' => 'Er is een import ingepland voor ' . $runTime->format('H:i')]);

                return Redirect::to('admin/importsuccess')->with(['type' => 'korting']);
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
    * This function will handle the downloads import
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
