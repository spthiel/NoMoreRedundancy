<?php


namespace Elspeth\Nomoreredundancy\Types;


interface IDividable extends INode
{

    /**
     * @return string
     */
    function getName();

    /**
     * @return string
     */
    function getInterfaces();

    /**
     * @param string $lllDomainModel
     * @return array
     */
    function build($lllDomainModel);

}