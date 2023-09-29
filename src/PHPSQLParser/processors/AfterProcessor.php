<?php

namespace PHPSQLParser\processors;

class AfterProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        return ['base_expr' => implode("", $tokens)];
    }
}