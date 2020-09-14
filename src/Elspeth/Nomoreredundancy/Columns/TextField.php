<?php


namespace Elspeth\Nomoreredundancy\Columns;


class TextField extends BaseColumn
{

    /**
     * TextField constructor.
     * @param string $fieldName Name of the column
     * @param bool $onChange Whether the backend form is supposed to reload on change
     * @param string|null $displayCond ['column'][*]['displayCond']
     * @param bool $exclude ['column'][*]['exclude']
     */
    public function __construct($fieldName, $onChange = false, $displayCond = null, $exclude = false)
    {
        parent::__construct($fieldName, "input", $onChange, $displayCond, $exclude);

        $this->addConfig("size", 30);
        $this->addConfig("eval", "trim");
    }
}