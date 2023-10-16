<?php

namespace PHPSQLParser\processors;

class TypeProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        $name = "";

        foreach ($tokens as $token){
            if(strtoupper($token) == "AS"){
                break;
            }
            $name .= $token;
        }
        return ['name' => trim($name), "base_expr" => implode($tokens)];
    }
}