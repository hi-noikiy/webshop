<?php

namespace App\Importers;

use App\Models\Product;
use Luna\Importer\Contracts\Importer;
use Luna\Importer\Importers\BaseImporter;

/**
 * Class ProductImporter
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ProductImporter extends BaseImporter implements Importer
{
    /**
     * @inheritdoc
     */
    public function getFilePath(): string
    {
        return storage_path('import/products.csv');
    }

    /**
     * @inheritdoc
     */
    public function getModel(): string
    {
        return Product::class;
    }

    /**
     * @inheritdoc
     */
    public function parseLine(array $data): array
    {
        return [
            'name'             => $data[0],
            'number'           => $data[3],
            'group'            => $data[4],
            'altNumber'        => $data[5],
            'stockCode'        => $data[7],
            'registered_per'   => $data[8],
            'packed_per'       => $data[9],
            'price_per'        => $data[10],
            'refactor'         => preg_replace("/\,/", '.', $data[12]),
            'ean'              => $data[13],
            'image'            => $data[14],
            'length'           => $data[16],
            'price'            => preg_replace("/\,/", '.', $data[17]),
            'vat'              => $data[18],
            'brand'            => $data[20],
            'series'           => $data[21],
            'type'             => $data[22],
            'special_price'    => ($data[23] === '' ? '0.00' : preg_replace("/\,/", '.', $data[23])),
            'action_type'      => $data[24],
            'keywords'         => $data[25],
            'related_products' => $data[26],
            'catalog_group'    => $data[27],
            'catalog_index'    => $data[28],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function validateLine(array $data): bool
    {
        return count($data) === 30;
    }

    /**
     * @inheritdoc
     */
    public function importSuccess()
    {
        \Artisan::call('check:related_products');
    }

    /**
     * @inheritdoc
     */
    public function getUniqueKey(): string
    {
        return 'number';
    }

    /**
     * @inheritdoc
     */
    public function shouldCleanup(): bool
    {
        return false;
    }
}