<?php

namespace PHPSQLParser\processors;

class ReturnsProcessor extends AbstractProcessor
{
    public function process($tokens)
    {

        $result = array();
        $result['sub_tree'] = $tokens['contains'];
        unset($tokens['contains']);
        $base_expr = "";
        foreach ($tokens as $token) {
            $base_expr .= $token;
        }
        $result['base_expr'] = trim($base_expr);

        return $result;
    }
}