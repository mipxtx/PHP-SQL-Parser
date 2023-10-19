<?php

namespace Analyser\Links;

abstract class AbstractItem
{
    const SEARCH = [")", "(", " ", "@", "=", "#", "-", ">", "<", ":", "?", "^", "/", "*", "+", ","];

    const BR = ["[", "]"];

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

        if ($name[0] == "#") {
            return true;
        }
        return false;
    }

    public static function sysName($in_sname)
    {
        $sname = $in_sname;

        $replace = [];
        foreach (self::SEARCH as $key) {
            $replace[] = "_";
        }
        $ss = [];
        $repl = [];

        preg_match_all("/\[(.*?)\]/",$sname,$out);

        foreach ($out[0] as $item){
            $ss[] = $item;
            $repl[] = str_replace(".", "_", $item);
        }
        $sname = str_replace($ss, $repl, $sname);
        $sname = str_replace(self::SEARCH, $replace, $sname);
        $sname = self::clear($sname);
        $sname = str_replace("..", ".dbo.", $sname);
        $sname = strtolower($sname);
        return trim($sname);
    }

    public static function clear($sname)
    {
        return trim(str_replace(self::BR, "", $sname));
    }

    protected function getNames($inName, Context $context)
    {

        $name = $inName;
        $sysName = $this->getSysName($name);
        if(substr_count($sysName,".") == 1){
            $name = "[".$context->getRoot()->getBase() . "].{$name}";
            $sysName = "".$context->getRoot()->getBase() . ".{$sysName}";
        }
        return [$name, $sysName];
    }
}