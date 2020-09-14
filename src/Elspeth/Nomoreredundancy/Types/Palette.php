<?php


namespace Elspeth\Nomoreredundancy\Types;


use Elspeth\Nomoreredundancy\Columns\BaseColumn;
use Elspeth\Nomoreredundancy\TCA\BaseTCA;
use Elspeth\Nomoreredundancy\TCA\FloatingHolder;
use Elspeth\Nomoreredundancy\Utils;
use http\Exception\RuntimeException;

class Palette extends ParentNode implements IDividable
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var BaseColumn[]
     */
    private $children;

    /**
     * @var BaseColumn
     */
    private $last;

    /**
     * Palette constructor.
     * @param string $name
     * @param Divider $parent
     */
    public function __construct($name, $parent)
    {
        parent::__construct($parent);
        $this->name = Utils::translateToKey($name);
    }

    /**
     * @param BaseColumn $child
     * @return Palette
     */
    public function addChild($child) {
        $this->children[] = $child;
        $this->last = $child;
        $child->setParent($this);
        return $this;
    }

    /**
     * @return INode[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return BaseTCA
     */
    public function getParentTCA() {
        return $this->getParent()->getParentTCA();
    }

    public function useLast() {
        return new FloatingHolder($this->last, $this);
    }

    /**
     * @return string
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * @return string[][]
     */
    function typehintPalette() {

        return [
            $this->name => [
                'showitem' => $this->getInterfaces()
            ]
        ];

    }

    /**
     * @return Divider
     */
    function close() {
        // Not actually an incompatible return type. Per constructor this can never be anything aside from Divider
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getParent();
    }

    /**
     * @param $paletteName
     * @return Palette
     */
    function addPalette($paletteName) {
        $this->last = null;
        return $this->getParent()->addPalette($paletteName);
    }

    /**
     * @return string
     */
    function getInterfaces()
    {
        $paletteString = "";

        $first = true;

        foreach ($this->children as $child) {
            if($child->isHidden()) {
                continue;
            }

            if($first) {
                $first = false;
                $paletteString .= $child->getName();
            } else {
                $paletteString .= ", " . $child->getName();
            }
        }

        return $paletteString;
    }

    /**
     * @param string $lllDomainModel
     * @return array
     */
    function build($lllDomainModel) {
        $columns = [];

        foreach ($this->children as $child) {
            $columns = array_merge($columns, $child->build($lllDomainModel));
        }

        return $columns;
    }

    function addColumnChild($column)
    {
        $this->addChild($column);
    }
}