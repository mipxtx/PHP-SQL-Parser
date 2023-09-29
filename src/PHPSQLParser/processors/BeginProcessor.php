<?php

namespace PHPSQLParser\processors;

class BeginProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        if(!isset($tokens['sub_tree'])){
            //print_r($tokens);
            //echo (new \Exception())->getTraceAsString();

        }

        $sub_tree = $tokens['sub_tree'];
        $startAt = $tokens['start_at'];
        unset($tokens['sub_tree']);
        unset($tokens['start_at']);
        $base_expr = "";
        $tokens = array_slice($tokens, 0, $startAt);
        foreach ($tokens as $token) {
            $base_expr .= $token;
        }
        return ['base_expr' => $base_expr, 'sub_tree' => $sub_tree,'expr_type' => 'scope'];
    }
}