<?php

namespace PHPSQLParser\processors;

class BeginProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        return ['base_expr' => implode ("", $tokens)];
    }
}