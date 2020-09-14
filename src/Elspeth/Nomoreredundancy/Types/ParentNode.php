<?php


namespace Elspeth\Nomoreredundancy\Types;


use Elspeth\Nomoreredundancy\Columns\BaseColumn;
use Elspeth\Nomoreredundancy\TCA\BaseTCA;
use http\Exception\RuntimeException;

abstract class ParentNode implements INode
{

    /**
     * @var BaseTCA|ParentNode
     */
    private $parent;

    /**
     * IParent constructor.
     * @param ParentNode|BaseTCA $parent
     */
    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return ParentNode|BaseTCA
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * @return BaseTCA
     */
    public function getParentTCA() {
        if($this->parent instanceof BaseTCA) {
            return $this->parent;
        }
        return $this->parent->getParentTCA();
    }

    /**
     * Useful for type hinting, not required
     * @return Palette
     */
    public function typehintPalette() {
        if(!($this instanceof Palette)) {
            throw new RuntimeException("Illegal State: Calling asPallette on " . __CLASS__);
        }
        return $this;
    }

    /**
     * Useful for type hinting, not required
     * @return Divider
     */
    public function typehintDivider() {
        if(!($this instanceof Divider)) {
            throw new RuntimeException("Illegal State: Calling asDivder on " . __CLASS__);
        }
        return $this;
    }

    /**
     * @param BaseColumn $column
     */
    abstract function addColumnChild($column);

    /**
     * @return INode[]
     */
    abstract public function getChildren();

}