<?php

namespace Analyser\Links;

abstract class AbstractItem
{
    public abstract function generate():string;

    public function getSysName($sname){
        return self::sysName($sname);
    }

    public static function sysName($sname){
        $sname = str_replace(["[","]",")","("," ","@","=","#","-",">","<",":","?"],"",$sname);
        return $sname;
    }
}