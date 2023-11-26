<?php

namespace Analyser\Links;

abstract class AbstractItem
{
    const SEARCH = [")", "(", " ", "@", "=", "#", "-", ">", "<", ":", "?", "^", "/", "*", "+", ",","\\"];

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
        $sname = self::transliterate($sname);
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
    static function transliterate($st) {
        $rep = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'z', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'c', 'ш' => 's', 'щ' => 's',
            'ь' => 'b', 'ы' => 'y', 'ъ' => 'b',
            'э' => 'e', 'ю' => 'y', 'я' => 'y',
            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Z', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'C', 'Ш' => 'S', 'Щ' => 'S',
            'Ь' => 'B', 'Ы' => 'Y', 'Ъ' => 'B',
            'Э' => 'E', 'Ю' => 'Y', 'Я' => 'Y',
        );

        $out = str_replace(array_keys($rep), array_values($rep), $st);
        return $out;
    }
}