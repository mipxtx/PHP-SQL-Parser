<?php

namespace PHPSQLParser\processors;

class ExecProcessor extends AbstractProcessor
{

    public function process($tokens)
    {


        //array_shift($tokens);



        $result = array();
        $base_expr = "";
        foreach ($tokens as $token) {
            $base_expr .= $token;

        }
        $result['base_expr'] = trim($base_expr);

        $words = [];
        foreach (explode(" ",str_replace(["\n","\t","\r"]," ", $base_expr)) as $w){
            if(trim($w)) {
                $words[] = $w;
            }
        }

        if(isset($words[1]) && $words[1] == "="){
            $name = $words[2];
        }else{
            $name = $words[0];
        }
        list($_,$args) = explode($name, $base_expr,2);

        $result['name'] = $name;
        $result['args'] = $args;

        return $result;
    }
}