<?php

namespace PHPSQLParser\processors;

class FunctionProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        $result = array();
        $base_expr = "";
        foreach ($tokens as $token){
            $base_expr .= $token;
        }
        $result['base_expr'] = trim($base_expr);

        preg_match('/([^\(]*).([^\)]*)/',$base_expr,$out);
        $result['name'] = $out[1];
        $result['args'] = $out[2];
        return $result;
    }
}