<?php

namespace PHPSQLParser\processors;

class ViewProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        $w = [];
        foreach ($tokens as $token){
            if(trim($token)){
                $w[] = $token;
            }
        }

        return ['name' => $w[0], 'base_expr' => trim(implode($tokens))];
    }
}