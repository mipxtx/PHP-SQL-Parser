<?php

namespace PHPSQLParser\processors;

class WhenProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        return ['base_expr' => implode("", $tokens)];
    }
}