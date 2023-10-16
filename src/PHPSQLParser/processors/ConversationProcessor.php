<?php

namespace PHPSQLParser\processors;

class ConversationProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        return ['name' => trim(implode($tokens)), 'base_expr' => implode($tokens)];
    }
}