<?php

namespace WTG\Importers;

use Carbon\Carbon;
use WTG\Models\Company;
use WTG\Models\Discount;
use Luna\Importer\Contracts\Importer;
use Luna\Importer\Importers\BaseImporter;

/**
 * Discount importer.
 *
 * @package     WTG
 * @subpackage  Importers
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DiscountImporter extends BaseImporter implements Importer
{
    const DISCOUNT_TYPE_GENERIC  = 'VA-221'; // Importance 10
    const DISCOUNT_TYPE_GROUP    = 'VA-220'; // Importance 20
    const DISCOUNT_TYPE_PRODUCT  = 'VA-261'; // Importance 30
    const DISCOUNT_TYPE_CUSTOMER = 'VA-260'; // Importance 40

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
        try {
            $importance = $this->convertTableToImportance($data[0]);
            $companyId = $this->findCompanyId($data[1]);
        } catch (\Exception $e) {
            \Log::warning("[Discount import] ".$e->getMessage());

            return [];
        }

        return [
            'importance'    => $importance,
            'company_id'    => $companyId,
            'product'       => (is_numeric($data[2]) ? $data[2] : dd($data[2])),
            'start_date'    => Carbon::createFromFormat('d-m-Y H:i:s', $data[3]),
            'end_date'      => Carbon::createFromFormat('d-m-Y H:i:s', $data[4]),
            'discount'      => str_replace(",", ".", $data[5]),
            'group_desc'    => $data[6] ?: null,
            'product_desc'  => $data[7] ?: null,
            'index'         => $data[0].$data[1].$data[2],
        ];
    }

    /**
     * Convert the table name to an importance level.
     *
     * @param  string  $table
     * @return string
     */
    public function convertTableToImportance($table): string
    {
        switch ($table) {
            case self::DISCOUNT_TYPE_GENERIC:
                return Discount::IMPORTANCE_GENERIC;
            case self::DISCOUNT_TYPE_GROUP:
                return Discount::IMPORTANCE_GROUP;
            case self::DISCOUNT_TYPE_PRODUCT:
                return Discount::IMPORTANCE_PRODUCT;
            case self::DISCOUNT_TYPE_CUSTOMER:
                return Discount::IMPORTANCE_CUSTOMER;
            default:
                return 0;
        }
    }

    /**
     * Find the company id for a customer number.
     *
     * @param  string|int  $customerNumber
     * @return int|null
     */
    public function findCompanyId($customerNumber): ?int
    {
        if (!$customerNumber) {
            return null;
        }

        /** @var Company $company */
        $company = Company::customerNumber($customerNumber)->firstOrFail();

        return $company->getAttribute('id');
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