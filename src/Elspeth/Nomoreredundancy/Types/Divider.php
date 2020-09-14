<?php


namespace Elspeth\Nomoreredundancy\Types;


use Elspeth\Nomoreredundancy\Columns\BaseColumn;
use Elspeth\Nomoreredundancy\TCA\BaseTCA;
use Elspeth\Nomoreredundancy\TCA\FloatingHolder;
use Elspeth\Nomoreredundancy\Utils;

class Divider extends ParentNode
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var BaseColumn
     */
    private $last;

    /**
     * @var IDividable[]
     */
    private $children = [];

    /**
     * Divider constructor.
     * @param BaseTCA $parent
     * @param string $name
     */
    public function __construct($parent, $name = "General")
    {
        parent::__construct($parent);
        $this->name = Utils::translateToKey($name);;
    }

    /**
     * @param IDividable $child
     * @return Divider
     */
    public function addChild($child)
    {
        $this->last = null;
        $this->children[] = $child;
        if($child instanceof BaseColumn) {
               $this->last = $child;
               $child->setParent($this);
        }
        return $this;
    }

    public function useLast() {
        if($this->last === null) {
            throw new \InvalidArgumentException("Call to useLast without last element. Call must follow directly after ");
        }
        return new FloatingHolder($this->last, $this);
    }

    /**
     * @return INode[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param string $paletteName
     * @return Palette
     */
    public function addPalette($paletteName)
    {
        $palette = new Palette($paletteName, $this);
        $this->addChild($palette);
        return $palette;
    }

    /**
     * @param string $lllDomainModel
     * @return array
     */
    public function build($lllDomainModel)
    {
        $types = "--div--;$lllDomainModel:div.$this->name";
        $palettes = [];
        $interfaces = "";
        $columns = [];

        $first = true;

        foreach ($this->children as $child) {

            if(!($child instanceof BaseColumn && $child->isHidden())) {
                if ($first) {
                    $first = false;
                    $interfaces .= $child->getInterfaces();
                } else {
                    $interfaces .= ", " . $child->getInterfaces();
                }

                if ($child instanceof Palette) {

                    $types .= ", --palette--;$lllDomainModel:palette." . $child->getName();
                    $palettes = array_merge($palettes, $child->typehintPalette());

                } else {
                    $types .= ", " . $child->getName();
                }
            }

            $columns = array_merge($columns, $child->build($lllDomainModel));

        }

        return [
            'types' => $types,
            'palettes' => $palettes,
            'interfaces' => $interfaces,
            'columns' => $columns
        ];

    }

    function addColumnChild($column)
    {
        $this->addChild($column);
    }
}