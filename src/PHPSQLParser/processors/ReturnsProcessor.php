<?php

namespace PHPSQLParser\processors;

class ReturnsProcessor extends AbstractProcessor
{
    public function process($tokens)
    {
        $result = [];
        $words = [];
        foreach ($tokens as $t){
            if(trim($t)){
                $words[] = $t;
            }
        }
        if(isset($words[1])){
            $result['name'] = $words[0];
            $result['type'] = $words[1];
        }
        $result['base_expr'] = trim(implode($tokens));

        return $result;
    }
}