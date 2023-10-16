<?php

namespace PHPSQLParser\processors;

class SimpleProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        return ['base_expr' => implode($tokens)];
    }
}