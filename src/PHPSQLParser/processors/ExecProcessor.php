<?php

namespace PHPSQLParser\processors;

class ExecProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        $w = [];

        foreach ($tokens as $t){
            if(trim($t)){
                $w[] = $t;
            }
        }

        if($w[0][0] == "("){
            return ['base_expr' => trim(implode($tokens))];
        }

        $result = array();
        $result['base_expr'] = trim(implode($tokens));
        $words = $w;
        if(isset($words[1]) && $words[1] == "="){
            $name = $words[2];
            array_shift($words);
            array_shift($words);
            array_shift($words);
        }else{
            $name = $words[0];
            array_shift($words);
        }
        //list($_,$args) = explode($name, $base_expr,2);

        $result['name'] = $name;
        $result['args'] = $words;

        return $result;
    }
}