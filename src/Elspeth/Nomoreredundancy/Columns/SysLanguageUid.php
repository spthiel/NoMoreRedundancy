<?php


namespace Elspeth\Nomoreredundancy\Columns;


use Elspeth\Nomoreredundancy\Types\ParentNode;

class SysLanguageUid extends BaseColumn
{

    /**
     * Field for the [ctrl][languageField] property, sets the key automatically
     * @param bool $onChange Whether the backend form is supposed to reload on change
     * @param false $exclude ['column'][*]['exclude']
     */
    public function __construct($onChange = true, $exclude = false)
    {
        parent::__construct("sys_language_uid", "select", $onChange, null, $exclude, false);

        $this->addConfig("renderType", "selectSingle");
        $this->addConfig("special", "languages");
        $this->addConfig("items", [
            ['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1, 'flags-multiple'],
        ]);
        $this->addConfig("default", 0);

    }

    /**
     * Add TransOrigPointerField and set TCA languageField as sideeffects
     *
     * @param ParentNode $parent
     */
    protected function onParentSet($parent)
    {
        $parent->getParentTCA()->addCtrl("languageField", "sys_language_uid");
        $parent->addColumnChild(new TransOrigPointerField());
    }

    /**
     * Override label property with default and delete description key
     *
     * @param string $lllDomainModel LLL:EXT key of the BaseTCA
     * @param array $columnConfig config of the column
     */
    protected function editRaw($lllDomainModel, &$columnConfig)
    {
        $columnConfig['label'] = 'LLL:EXT:lang/locallang_general.xlf:LGL.language';
        unset($columnConfig['description']);
    }

}


