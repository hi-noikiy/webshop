<?php

namespace App\Console\Commands;

class ListUnusedImages extends \Illuminate\Console\Command
{
	protected $name = 'list:unused-images';

	public function handle()
	{
		$imgPath = public_path('img/products');
		$productImages = array_unique(\App\Product::all(['image'])->pluck('image')->all());

		//dd($productImages);

		$imageFiles = array_map(function ($item) use ($imgPath) {
			return str_replace($imgPath . "/", "", $item);
		}, \File::files($imgPath));

		$unusedImages = array_values(array_diff($imageFiles, $productImages));
		
		$tempFile = tempnam(storage_path('app'), "");
		$handle = fopen($tempFile, "w");
		
		foreach ($unusedImages as $img) {
			if (\App\Product::where('image', $img)->exists()) {
				$this->output->printLn("Skipped: " . $img);
				continue;
			}

			fputcsv($handle, [$img]);
		}
	
		fclose($handle);

		$this->output->printLn("Data written to: " . $tempFile);
	}
}
