<?php


namespace Elspeth\Nomoreredundancy\TCA;


use Elspeth\Nomoreredundancy\Columns\BaseColumn;
use Elspeth\Nomoreredundancy\Types\Divider;
use Elspeth\Nomoreredundancy\Types\INode;
use Elspeth\Nomoreredundancy\Types\ParentNode;
use Elspeth\Nomoreredundancy\Utils;
use Exception;
use http\Exception\RuntimeException;

class BaseTCA
{

    /**
     * @var string
     */
    private $domainmodel;

    /**
     * @var string
     */
    protected $lllDomainModel;

    /**
     * @var Divider[]
     */
    private $children = [];

    /**
     * @var array
     */
    private $ctrl = [
        'delete' => 'delete'
    ];

    /**
     * @var Divider
     */
    private $hidden;

    /**
     * BaseTCA constructor.
     * @param string $extension
     * @param string $domainmodel
     */
    public function __construct($extension, $domainmodel)
    {
        $this->domainmodel = $domainmodel;
        $this->lllDomainModel = "LLL:EXT:$extension/Resources/Private/Language/locallang_csh_tx_{$extension}_domain_model_$domainmodel.xlf";
        $this->ctrl['iconfile'] = "EXT:$extension/Resources/Public/Images/tx_{$extension}_domain_model_$domainmodel.gif";
        $this->ctrl['title'] = "EXT:$extension/Resources/Private/Language/locallang_db.xlf:tx_{$extension}_domain_model_$domainmodel";
        $this->hidden = $this->add("hidden");
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function addCtrl($key, $value) {
        $this->ctrl[$key] = $value;
        return $this;
    }

    public function getColumnByName($name) {
        $name = Utils::translateToKey($name);
        /**
         * @var INode[]
         */
        $stack = $this->children;

        for($i = 0; $i < count($stack); $i++) {

            $item = $stack[$i];

            if($item instanceof BaseColumn) {

                if(strcasecmp($item->getName(), $name) === 0) {

                    return $item;

                }
            } else if($item instanceof ParentNode){
                $stack = array_merge($stack, $item->getChildren());
            }
        }
        return null;
    }

    /**
     * Sets $TCA['ctrl']['label'] To name of column.
     * Use addChild()->useLast()->asLabel()
     * @param BaseColumn $column
     * @return BaseTCA $this
     */
    public function setLabel(BaseColumn $column) {
        $this->addCtrl('label', $column->getName());
        return $this;
    }

    public function addAltLabel(BaseColumn $column) {
        if(!isset($this->ctrl['label_alt'])) {
            $this->ctrl['label_alt'] = $column->getName();
        } else {
            $this->ctrl['label_alt'] .= ", " . $column->getName();
        }
        return $this;
    }

    public function setAltLabel(BaseColumn ...$column) {
        $this->addCtrl('label_alt', join(", ", array_map(function ($value) { return $value->getName();}, $column)));
        return $this;
    }

    public function setAdminOnly() {
        $this->addCtrl('adminOnly', 1);
        return $this;
    }

    public function addCrdate() {
        $this->addCtrl("crdate", "crdata");
        return $this;
    }

    public function addCruserid() {
        $this->addCtrl("cruserid", "cruserid");
        return $this;
    }

    public function addCopyAfterDuplField(BaseColumn $column) {
        if(!isset($this->ctrl['copyAfterDuplFields'])) {
            $this->ctrl['copyAfterDuplFields'] = $column->getName();
        } else {
            $this->ctrl['copyAfterDuplFields'] .= ", " . $column->getName();
        }
        return $this;
    }

    public function setCopyAfterDuplFields(BaseColumn ...$columns) {
        $this->addCtrl('copyAfterDuplFields', join(", ", array_map(function ($value) { return $value->getName();}, $columns)));
        return $this;
    }

    public function addDefaultSortby(BaseColumn $column, $direction = "ASC") {
        if(!isset($this->ctrl['default_sortby'])) {
            $this->ctrl['default_sortby'] = $column->getName() . " $direction";
        } else {
            $this->ctrl['default_sortby'] .= ", " . $column->getName() . " $direction";
        }
        return $this;
    }

    public function setDefaultSortby(BaseColumn ...$columns) {
        $this->addCtrl('default_sortby', join(", ", array_map(function ($value) { return $value->getName();}, $columns)));
        return $this;
    }

    public function setFormattedLabelUserFuncOptions($options) {
        $this->ctrl['formattedLabel_userFunc_options'] = $options;
        return $this;
    }

    public function setGroupName($name) {
        $this->ctrl['groupName'] = $name;
        return $this;
    }

    public function setHideAtCopy() {
        $this->ctrl['hideAtCopy'] = true;
        return $this;
    }

    public function setHideTable() {
        $this->ctrl['hideTable'] = true;
        return $this;
    }

    public function unsetDelete() {
        unset($this->ctrl['delete']);
        return $this;
    }

    public function setStatic() {
        $this->ctrl['static'] = true;
        return $this;
    }

    public function setLabelAltForce() {
        $this->ctrl['label_alt_force'] = true;
        return $this;
    }

    public function setLabelUserFuncOptions($options) {
        $this->ctrl['label_userFunc_options'] = $options;
        return $this;
    }

    public function setOrigUid() {
        $this->ctrl['origUid'] = 't3_origuid';
        return $this;
    }

    /**
     * @param string $string String used for appending, may contain ###LLLMODEL### to be replaced with the auto generated lllDomainModel
     */
    public function setPrependAtCopy($string) {
        $string = str_replace("###LLLMODEL###", $this->lllDomainModel, $string);
        $this->ctrl['prependAtCopy'] = $string;
        return $this;
    }

    public function setReadOnly() {
        $this->ctrl['readOnly'] = true;
        return $this;
    }

    public function setRootLevel($level) {
        $this->ctrl['rootLevel'] = $level;
    }

    public function addSearchField(BaseColumn $column, $direction = "ASC") {
        if(!isset($this->ctrl['searchFields'])) {
            $this->ctrl['searchFields'] = $column->getName() . " $direction";
        } else {
            $this->ctrl['searchFields'] .= ", " . $column->getName() . " $direction";
        }
        return $this;
    }

    public function setSearchField(BaseColumn ...$columns) {
        $this->addCtrl('searchFields', join(", ", array_map(function ($value) { return $value->getName();}, $columns)));
        return $this;
    }

    public function setSecurity($ignoreWebMountRestriction, $ignoreRootLevelRestriction) {
        $this->ctrl['security'] = [
            'ignoreWebMountRestriction' => $ignoreWebMountRestriction,
            'ignoreRootLevelRestriction' => $ignoreRootLevelRestriction,
        ];
        return $this;
    }

    public function addSetToDefaultOnCopy(BaseColumn $column, $direction = "ASC") {
        if(!isset($this->ctrl['setToDefaultOnCopy'])) {
            $this->ctrl['setToDefaultOnCopy'] = $column->getName() . " $direction";
        } else {
            $this->ctrl['setToDefaultOnCopy'] .= ", " . $column->getName() . " $direction";
        }
        return $this;
    }

    public function setSetToDefaultOnCopy(BaseColumn ...$columns) {
        $this->addCtrl('setToDefaultOnCopy', join(", ", array_map(function ($value) { return $value->getName();}, $columns)));
        return $this;
    }

    public function addShadowColumnsForNewPlaceholders(BaseColumn $column, $direction = "ASC") {
        if(!isset($this->ctrl['shadowColumnsForNewPlaceholders'])) {
            $this->ctrl['shadowColumnsForNewPlaceholders'] = $column->getName() . " $direction";
        } else {
            $this->ctrl['shadowColumnsForNewPlaceholders'] .= ", " . $column->getName() . " $direction";
        }
        return $this;
    }

    public function setShadowColumnsForNewPlaceholders(BaseColumn ...$columns) {
        $this->addCtrl('shadowColumnsForNewPlaceholders', join(", ", array_map(function ($value) { return $value->getName();}, $columns)));
        return $this;
    }

    public function addShadowColumnsForMovePlaceholders(BaseColumn $column, $direction = "ASC") {
        if(!isset($this->ctrl['shadowColumnsForMovePlaceholders'])) {
            $this->ctrl['shadowColumnsForMovePlaceholders'] = $column->getName() . " $direction";
        } else {
            $this->ctrl['shadowColumnsForMovePlaceholders'] .= ", " . $column->getName() . " $direction";
        }
        return $this;
    }

    public function setShadowColumnsForMovePlaceholders(BaseColumn ...$columns) {
        $this->addCtrl('shadowColumnsForMovePlaceholders', join(", ", array_map(function ($value) { return $value->getName();}, $columns)));
        return $this;
    }

    public function setSortby() {
        $this->ctrl['sortby'] = 'sorting';
        return $this;
    }

    public function setThumbnail(BaseColumn $column) {
        $this->ctrl['sortby'] = $column->getName();
        return $this;
    }

    public function setTransOrigDiffSourceField() {
        $this->ctrl['transOrigDiffSourceField'] = "l10n_diffsource";
        return $this;
    }
    
    public function setTstamp() {
        $this->ctrl['tstamp'] = 'tstamp';
        return $this;
    }

    /**
     * @throws Exception Unsupported Function 
     */
    public function setType() {
        //TODO: Unsupported - Think of chainable way for having multiple types
        throw new Exception("Unsupported function");
    }
    
    public function setTypeiconClasses($array) {
        $this->ctrl['typeicon_classes'] = $array;
        return $this;
    }

    public function setTypeiconColumn($array) {
        $this->ctrl['typeicon_column'] = $array;
        return $this;
    }

    public function addUseColumnsForDefaultValues(BaseColumn $column, $direction = "ASC") {
        if(!isset($this->ctrl['useColumnsForDefaultValues'])) {
            $this->ctrl['useColumnsForDefaultValues'] = $column->getName() . " $direction";
        } else {
            $this->ctrl['useColumnsForDefaultValues'] .= ", " . $column->getName() . " $direction";
        }
        return $this;
    }

    public function setUseColumnsForDefaultValues(BaseColumn ...$columns) {
        $this->addCtrl('useColumnsForDefaultValues', join(", ", array_map(function ($value) { return $value->getName();}, $columns)));
        return $this;
    }

    /**
     * @param null|bool $disabled
     * @param null|bool $starttime
     * @param null|bool $endtime
     * @param null|bool $fe_group
     */
    private function setEnabledColumns($disabled = null, $starttime = null, $endtime = null, $fe_group = null) {
        $enabledColumns = $this->ctrl['enabledcolumns'];
        if(!isset($enabledColumns)) {
            $enabledColumns = [];
        }

        $this->applyEnabledColumns($enabledColumns, "disabled", $disabled);
        $this->applyEnabledColumns($enabledColumns, "starttime", $starttime);
        $this->applyEnabledColumns($enabledColumns, "endtime", $endtime);
        $this->applyEnabledColumns($enabledColumns, "fe_group", $fe_group);
    }

    private function applyEnabledColumns(&$enabledColumns, $fieldname, $set) {
        if($set === null) {
            return;
        }

        if($set) {
            $enabledColumns[$fieldname] = $fieldname;
        } else {
            unset($enabledColumns[$fieldname]);
        }
    }

    /**
     * @return string
     */
    protected function getLLLKey() {
        return $this->lllDomainModel;
    }

    /**
     * @param array $parameters
     * @return int|void
     */
    public function label_userFunc(&$parameters = null) {
        return -1;
    }

    /**
     * @param array $parameters
     * @return int|void
     */
    public function fomattedLabel_userFunc(&$parameters = null) {
        return -1;
    }

    /**
     * @param Divider|string $divider
     * @return Divider
     */
    public function add($divider) {
        if(is_string($divider)) {
            $divider = new Divider($this, $divider);
        }
        $this->children[] = $divider;
        return $divider;
    }

    public function build()
    {
        $tca = [];
        $ctrl = $this->ctrl;
        $columns = [];
        $types = "";
        $palettes = [];
        $interfaces = "";

        if($this->label_userFunc() !== -1) {
                $ctrl['label_userFunc'] = function(&$parameters) {$this->label_userFunc($parameters);};
        }

        if($this->fomattedLabel_userFunc() !== -1) {
            $ctrl['label_userFunc'] = function(&$parameters) {$this->fomattedLabel_userFunc($parameters);};
        }

        $first = true;

        foreach ($this->children as $divider) {
            $out = $divider->build($this->getLLLKey());

            if($first) {
                $first = false;
                $types .= $out['types'];
                $interfaces .= $out['interfaces'];
            } else {
                $types .= ", " . $out['types'];
                $interfaces .= ", " . $out['interfaces'];
            }

            $palettes = array_merge($palettes, $out['palettes']);
            $columns = array_merge($columns, $out['columns']);
        }

        return [
            'ctrl' => $ctrl,
            'interface' => [
                'showRecordFieldList' => $interfaces
            ],
            'types' => [
                0 => [
                    'showitem' => $types
                ]
            ],
            'palettes' => $palettes,
            'columns' => $columns
        ];
    }

}