<?php
namespace PHPSQLParser\processors;

class ExecuteProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        $result = array();
        $base_expr = "";
        foreach ($tokens as $token){
            $base_expr .= $token;
        }
        $result['base_expr'] = trim($base_expr);


        return $result;
    }
}