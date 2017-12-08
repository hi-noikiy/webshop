<?php

namespace WTG\Importers;

use WTG\Models\Product;
use Luna\Importer\Contracts\Importer;
use Luna\Importer\Importers\BaseImporter;

/**
 * Product importer.
 *
 * @package     WTG
 * @subpackage  Importers
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
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
            'sku'              => $data[3],
            'group'            => $data[4],
            'alt_sku'          => $data[5],
//            'stockCode'        => $data[7],
            'registered_per'   => $data[8],
            'packed_per'       => $data[9],
            'price_per'        => $data[10],
            'refactor'         => str_replace(",", ".", $data[12]),
//            'supplier'         => $data[13],
            'ean'              => $data[14],
            'image'            => $data[15],
            'length'           => $data[17],
            'price'            => str_replace(",", ".", $data[18]),
//            'vat'              => $data[20],
            'brand'            => $data[21],
            'series'           => $data[22],
            'type'             => $data[23],
            'special_price'    => ($data[24] === '' ? null : str_replace(",", ".", $data[24])),
            'action_type'      => $data[25] ?: null,
            'keywords'         => $data[26],
            'related_products' => $data[27],
            'catalog_group'    => $data[28],
            'catalog_index'    => utf8_encode($data[29]),
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
        return 'sku';
    }

    /**
     * @inheritdoc
     */
    public function shouldCleanup(): bool
    {
        return false;
    }
}