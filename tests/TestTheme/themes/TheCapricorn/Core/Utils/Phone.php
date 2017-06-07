<?php

namespace TheCapricorn\Core\Utils;

class Phone
{

    /**
     * remove space from phone number and replace the + by 00
     *
     * @return String
     */
    
    public static function format(String $s)
    {   
        $s = str_replace('+', '00' , $s);
        return preg_replace("/[^0-9,.]/", "", $s);
    }
}