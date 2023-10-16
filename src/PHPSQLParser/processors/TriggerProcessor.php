<?php

namespace PHPSQLParser\processors;

class TriggerProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        $words = [];
        foreach ($tokens as $tt){
            if(trim($tt)){
                $words[] = $tt;
            }
        }

        if(!isset($words[2])){
            print_r($tokens);
            throw new \Exception("no table on trigger");
        }

        return ['base_expr' => implode($tokens), "name" => trim($words[0]), "table" => trim($words[2])];
    }
}