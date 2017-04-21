<?php

namespace App\Importers;

use App\Models\Discount;
use Luna\Importer\Contracts\Importer;

/**
 * Class DiscountImporter
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DiscountImporter extends BaseImporter implements Importer
{
    /**
     * @inheritdoc
     */
    public function getFilePath(): string
    {
        return storage_path('import/discounts.csv');
    }

    /**
     * @inheritdoc
     */
    public function getModel(): string
    {
        return Discount::class;
    }

    /**
     * @inheritdoc
     */
    public function parseLine(array $data): array
    {
        return [
            'table'         => $data[0],
            'User_id'       => ($data[1] !== '' ? $data[1] : 0),
            'product'       => (is_numeric($data[2]) ? $data[2] : 0),
            'start_date'    => $data[3],
            'end_date'      => $data[4],
            'discount'      => $data[5],
            'group_desc'    => $data[6],
            'product_desc'  => $data[7],
            'index'         => $data[0].$data[1].$data[2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getUniqueKey(): string
    {
        return 'index';
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return 'discount';
    }

    /**
     * @inheritdoc
     */
    public function validateLine(array $data): bool
    {
        return count($data) === 8;
    }

    /**
     * @inheritdoc
     */
    public function shouldCleanup(): bool
    {
        return true;
    }
}