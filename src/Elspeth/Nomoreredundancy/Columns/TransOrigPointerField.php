<?php

namespace Elspeth\Nomoreredundancy\Columns;

class TransOrigPointerField extends BaseColumn
{

    /**
     * Field for the
     *
     * @param false $exclude
     */
    public function __construct($exclude = false)
    {
        parent::__construct("l10n_parent", "select", false, 'FIELD:sys_language_uid:>:0', $exclude, false);

        $this->addConfig("renderType", "selectSingle");
        $this->addConfig("items", [['', 0]]);
        $this->addConfig("foreign_table", "tt_content");
        $this->addConfig("foreign_table_where", "AND tt_content.pid=###CURRENT_PID### AND tt_content.sys_language_uid IN (-1,0)");
        $this->addConfig("default",0);
    }

    protected function onParentSet($parent)
    {
        $parent->getParentTCA()->addCtrl("transOrigPointerField", "l10n_parent");
    }

    protected function editRaw($lllDomainModel, &$columnConfig)
    {
        $columnConfig['label'] = 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent';
    }

}