<?php


namespace Elspeth\Nomoreredundancy\TCA;


use Elspeth\Nomoreredundancy\Columns\BaseColumn;
use Elspeth\Nomoreredundancy\Types\ParentNode;

class FloatingHolder
{

    /**
     * @var BaseColumn
     */
    private $value;

    /**
     * @var ParentNode
     */
    private $parent;

    /**
     * @param BaseColumn $value
     * @param ParentNode $parent
     */
    public function __construct($value, $parent) {
        $this->value = $value;
        $this->parent = $parent;
    }

    public function asLabel() {

        $this->parent->getParentTCA()->setLabel($this->value);
        return $this->parent;
    }

    public function asAdditionalAltLabel() {

        $this->parent->getParentTCA()->addAltLabel($this->value);
        return $this->parent;
    }

    public function asAdditionalCopyAfterDuplField() {

        $this->parent->getParentTCA()->addCopyAfterDuplField($this->value);
        return $this->parent;
    }

    /**
     * @param string $direction "ASC" or "DESC"
     * @return ParentNode
     */
    public function asAdditionalDefaultSortby($direction = "ASC") {

        $this->parent->getParentTCA()->addDefaultSortby($this->value, $direction);
        return $this->parent;
    }

    public function asAdditionalSearchField() {
        $this->parent->getParentTCA()->addSearchField($this->value);
        return $this->parent;
    }

    public function asAdditionalSetToDefaultOnCopy() {
        $this->parent->getParentTCA()->addSetToDefaultOnCopy($this->value);
        return $this->parent;
    }

    public function asAdditionalColumnForDefaultValues() {
        $this->parent->getParentTCA()->addUseColumnsForDefaultValues();
    }

}