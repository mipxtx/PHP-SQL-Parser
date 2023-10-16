<?php

namespace PHPSQLParser\processors;

class EndProcessor  extends AbstractProcessor
{

    public function process($tokens)
    {
        return ['base_expr' => trim(implode($tokens))];
    }
}