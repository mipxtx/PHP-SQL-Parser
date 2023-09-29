<?php

namespace PHPSQLParser\processors;

class AlterProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        return ['base_expr' => implode("", $tokens)];
    }
}