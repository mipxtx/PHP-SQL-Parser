<?php

namespace PHPSQLParser\processors;

class MergeProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        $w = [];
        foreach ($tokens as $t){
            if(trim($t)){
                $w[] = $t;
            }
        }
        if(!isset($w[1])){
            return [];
        }
        $alias = $w[1];
        if(strtoupper($w[1]) == "AS"){
            $alias = $w[2];
        }
        $ret =  ["name" => $w[0], "alias" => $alias, "base_expr" => implode($tokens)];

        return $ret;
    }
}