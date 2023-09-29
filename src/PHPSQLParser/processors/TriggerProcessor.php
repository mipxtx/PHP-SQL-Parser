<?php

namespace PHPSQLParser\processors;

class TriggerProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
       $base_expr = implode("", $tokens);
       preg_match("/(\[.*\])\s+ON\s+(\S*)/", str_replace("\n", " ", $base_expr), $out);
       return ['base_expr' => $base_expr, "name" => trim($out[1]), "table"=>trim($out[2])];
    }
}