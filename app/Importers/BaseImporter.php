<?php

namespace App\Importers;

use Luna\Importer\Contracts\Importer;

abstract class BaseImporter extends \Luna\Importer\Importers\BaseImporter implements Importer
{
    /**
     * Get the import type
     *
     * @return string
     */
    abstract public function getType(): string;
}