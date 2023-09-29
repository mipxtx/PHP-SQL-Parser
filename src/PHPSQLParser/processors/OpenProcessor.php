<?php

namespace PHPSQLParser\processors;

class OpenProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        return ['base_expr' => implode($tokens)];
    }
}