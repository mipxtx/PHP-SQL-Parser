<?php

namespace PHPSQLParser\processors;

class ReturnProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        return ['base_expr' => implode($tokens)];
    }
}