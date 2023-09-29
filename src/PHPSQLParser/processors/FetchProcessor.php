<?php

namespace PHPSQLParser\processors;

class FetchProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        return ['base_expr' => implode($tokens)];
    }
}