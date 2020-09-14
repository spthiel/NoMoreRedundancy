<?php


namespace Elspeth\Nomoreredundancy;


class Utils
{

    /**
     * @param string $thing
     * @return string
     */
    public static function translateToKey($thing)
    {

        $thing = str_replace(" " , "_" , $thing);
        $thing = strtolower($thing);

        return $thing;
    }

}