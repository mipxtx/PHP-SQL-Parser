<?php

namespace PHPSQLParser\processors;

class DeclareProcessor extends AbstractProcessor
{

    public function process($tokens)
    {

        $result = array();
        $base_expr = "";
        foreach ($tokens as $token){
            $base_expr .= $token;
        }
        $result['base_expr'] = trim($base_expr);
        /*
        preg_match('/(\S+)\s+(\S+)/',$base_expr,$out);
        if(!isset($out[1])){
            echo $base_expr . "\n";
        }
        $result['name'] = $out[1];
        $result['type'] = $out[2];
        */
        return $result;
    }
}