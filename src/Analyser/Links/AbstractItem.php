<?php

namespace Analyser\Links;

abstract class AbstractItem
{
    const SEARCH = [")", "(", " ", "@", "=", "#", "-", ">", "<", ":", "?", "^", "/", "*", "+",","];

    const BR = ["[", "]"];

    const SKIP = [
        "deleted",
        'inserted',
        "debug",
        "logging",
        "cursor local for",
        'string_split',
        'openjson',
        "sysname",
        'tinyint',
        'int',
        'numeric',
        'nvarchar',
        'datetime',
        'geography',
        'real',
        'table',
        'varchar',
    ];

    public abstract function generate(): string;

    abstract public function getName(): string;

    public function getSysName($sname)
    {
        return self::sysName($sname);
    }



    public function skipName($name)
    {
        if (!$name) {
            return true;
        }

        if($name[0]=="#"){
            return true;
        }
        return false;
    }

    public static function sysName($sname)
    {

        $replace = [];
        foreach (self::SEARCH as $key) {
            $replace[] = "_";
        }
        $sname = str_replace(self::SEARCH, $replace, $sname);
        $sname = self::clear($sname);
        $sname = str_replace("..", ".", $sname);
        $sname = strtolower($sname);
        return trim($sname);
    }

    public static function clear($sname)
    {
        return trim(str_replace(self::BR, "", $sname));
    }
}