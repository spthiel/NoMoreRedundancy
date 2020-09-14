<?php


namespace Elspeth\Nomoreredundancy\Columns;


class Passthrough extends BaseColumn
{

    /**
     * Passthrough constructor.
     * @param string $fieldName Name of the column
     */
    public function __construct($fieldName)
    {
        parent::__construct($fieldName, "passthrough", false, null, false, true);
    }

    /**
     * Removing label and description for convention purposes and to keep the configuration clean
     *
     * @param string $lllDomainModel
     * @param array $columnConfig
     */
    protected function editRaw($lllDomainModel, &$columnConfig)
    {
        unset($columnConfig['label']);
        unset($columnConfig['description']);
    }

}