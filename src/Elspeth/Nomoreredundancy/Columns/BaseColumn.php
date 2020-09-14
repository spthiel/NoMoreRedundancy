<?php


namespace Elspeth\Nomoreredundancy\Columns;


use Elspeth\Nomoreredundancy\TCA\BaseTCA;
use Elspeth\Nomoreredundancy\Types\IDividable;
use Elspeth\Nomoreredundancy\Types\Divider;
use Elspeth\Nomoreredundancy\Types\INode;
use Elspeth\Nomoreredundancy\Types\ParentNode;
use Elspeth\Nomoreredundancy\Types\Palette;
use Elspeth\Nomoreredundancy\Utils;

abstract class BaseColumn implements IDividable
{

    /**
     * @var string Stores the name of the column
     */
    private $fieldName;

    /**
     * @var bool Stores whether the backend form is supposed to reload on change
     */
    private $onChange;

    /**
     * @var string Stores the ['column'][*]['config']['type]
     */
    private $type;

    /**
     * @var string|null Stores ['column'][*]['displayCond']
     */
    private $displayCond;

    /**
     * @var bool Stores ['column'][*]['exclude']
     */
    private $exclude;

    /**
     * @var bool If true, column will not be added to types or palettes
     */
    private $hidden;

    /**
     * @var ParentNode The parent of the Column - Either a Palette or a Divider - Used for chaining
     */
    private $parent;

    /**
     * @var array Stores ['column'][*]['config'][*]
     */
    private $attributes = [];

    /**
     * BaseColumn constructor.
     * @param string $fieldName Name of the column
     * @param string $type ['column'][*]['config']['type']
     * @param bool $onChange Whether the backend form is supposed to reload on change
     * @param string|null $displayCond ['column'][*]['displayCond']
     * @param bool $exclude ['column'][*]['exclude']
     * @param bool $hidden If true, column will not be added to types or palettes
     */
    public function __construct($fieldName, $type, $onChange = false, $displayCond = null, $exclude = false, $hidden = false)
    {
        $this->fieldName = Utils::translateToKey($fieldName);
        $this->onChange = $onChange;
        $this->type = $type;
        $this->displayCond = $displayCond;
        $this->exclude = $exclude;
        $this->hidden = $hidden;
    }

    /**
     * Function used by extension for allowing of chaining of elements easily. Should not be called from the outside
     * @param ParentNode $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        $this->onParentSet($parent);
    }

    /**
     * For columns that require multiple columns, e.g. sys_language_uid doesn't appear without l10n_parent
     * Override to add additional columns with $parent->addColumnChild() or trigger other sideeffects
     * @param ParentNode $parent ;
     */
    protected function onParentSet($parent)
    {
    }

    /**
     * Overrides the name of the column, can be used when extending classes which remove the fieldName parameter from the constructor
     * @param string $fieldName New name of the column
     * @see TranslationSource
     *
     */
    protected function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * Returns the name of the column
     *
     * @return string Name of the column
     */
    public function getName()
    {
        return $this->fieldName;
    }

    /**
     * Returns the string of TCA['interface']['showRecordFieldList'] for this element
     *
     * @return string
     */
    public function getInterfaces()
    {
        return $this->getName();
    }

    /**
     * Pushes a key value pair to [columns][*][config]
     *
     * @param string $key key of the pair
     * @param mixed $value value of the pair
     */
    protected function addConfig($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Override this for the ability of raw editing of the columnConfig, should not replace the config
     * As example @see Passthrough
     *
     * @param string $lllDomainModel LLL:EXT key of the BaseTCA
     * @param array $columnConfig Config of the column after all defaults are set
     */
    protected function editRaw($lllDomainModel, &$columnConfig)
    {

    }

    /**
     * Returns whether the column should not be added to showRecordFieldList and type
     *
     * @return bool
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * Generates the configuration of the column
     *
     * @param string $lllDomainModel LLL:EXT key of the BaseTCA
     * @return array[]
     */
    public function build($lllDomainModel)
    {

        $columnConfig = [
            'label' => "$lllDomainModel:$this->fieldName",
            'description' => "$lllDomainModel:$this->fieldName.descriptiontext",
            'config' => [
                'type' => $this->type
            ]
        ];

        if ($this->onChange) {
            $columnConfig['onChange'] = 'reload';
        }

        if ($this->displayCond !== null) {
            $columnConfig['displayCond'] = $this->displayCond;
        }

        if ($this->exclude) {
            $columnConfig['exclude'] = true;
        }

        foreach ($this->attributes as $key => $value) {
            $columnConfig['config'][$key] = $value;
        }

        $this->editRaw($lllDomainModel, $columnConfig);

        return [
            $this->fieldName => $columnConfig
        ];
    }
}