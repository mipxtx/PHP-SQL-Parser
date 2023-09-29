<?php

namespace PHPSQLParser\processors;

class ProcedureProcessor extends AbstractProcessor
{

    public function process($tokens)
    {
        $result = array();
        $base_expr = "";

        $first = array_shift($tokens);
        foreach ($tokens as $token) {
            $base_expr .= $token;
        }

        $result['base_expr'] = trim($first . $base_expr);
        preg_match('/([^ ]*)(.*)/', trim(str_replace("\n"," ",$base_expr)), $out);


        $result['name'] = $out[1];
        $result['args'] = explode(",", trim($out[2]));
        return $result;
    }
}