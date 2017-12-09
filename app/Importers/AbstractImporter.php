<?php

namespace WTG\Importers;

use Luna\Importer\Contracts\Importer;
use Luna\Importer\Importers\BaseImporter;

/**
 * Abstract importer.
 *
 * @package     WTG
 * @subpackage  Importers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
abstract class AbstractImporter extends BaseImporter implements Importer
{
    /**
     * Get the import type
     *
     * @return string
     */
    abstract public function getType(): string;
}