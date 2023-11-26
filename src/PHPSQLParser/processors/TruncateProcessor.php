<?php

namespace PHPSQLParser\processors;

class TruncateProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        return ['base_expr' => implode($tokens)];
    }
}