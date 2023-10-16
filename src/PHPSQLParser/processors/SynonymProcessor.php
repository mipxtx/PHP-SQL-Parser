<?php

namespace PHPSQLParser\processors;

class SynonymProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        $name = "";
        $target = "";
        $state = "";

        foreach ($tokens as $token){
            switch ($state){
                case "target":
                    $target .= $token;
                    break;
                default:
                    if(strtoupper($token) == "FOR"){
                        $state = "target";
                    }else{
                        $name .= $token;
                    }
            }

        }

        return [
            'name' => trim($name),
            'target' => trim($target),
            'base_expr' => trim(implode($tokens))
        ];
    }
}