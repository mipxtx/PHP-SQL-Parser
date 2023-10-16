<?php

namespace PHPSQLParser\processors;

class DeclareProcessor extends AbstractProcessor
{

    public function process($tokens)
    {

        $result = array();
        $base_expr = "";
        $nbexpr = "";
        foreach ($tokens as $token){
            $base_expr .= $token;
            if($token[0] != "("){
                $nbexpr .= $token;
            }


        }
        $result['base_expr'] = trim($base_expr);

        foreach(explode (",",$nbexpr) as $expr){

            @list($dec,$default) = explode("=",$expr);
            if($default) {
                $default = trim($default);
            }
            $dec = trim($dec);


            preg_match("/(\S+)(.*)/",$dec,$out);
            $name = trim($out[1]);
            $type = trim($out[2]);

            $result['items'][] = ['name' => $name, 'type'=>$type, 'default' => $default];
        }

        return $result;
    }
}