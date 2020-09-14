<?php


namespace Elspeth\Nomoreredundancy\Columns;


use Elspeth\Nomoreredundancy\Types\ParentNode;

class TranslationSource extends TransOrigPointerField
{

    /**
     * Field for the [ctrl][translationSource] property, sets the key automatically
     * @param false $exclude ['column'][*]['exclude']
     */
    public function __construct($exclude = false)
    {
        parent::__construct($exclude);
        $this->setFieldName("l10n_source");
    }

    /**
     * Adds the column as translationSource as sideeffect
     *
     * @param ParentNode $parent
     */
    protected function onParentSet($parent)
    {
        $parent->getParentTCA()->addCtrl("translationSource", "l10n_source");
    }

}