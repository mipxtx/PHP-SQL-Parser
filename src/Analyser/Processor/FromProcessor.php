<?php

namespace Analyser\Processor;

use Analyser\Links\Link;
use Analyser\Links\LinkPack;

class FromProcessor extends AbstractProcessor
{

    public function process(array $tree, array $root, array $context): LinkPack
    {

        $pack = new LinkPack();
        $type = "from";

        if (in_array('SET', $context)) {
            $type = "select";
        }

        if (in_array('OPEN', $context)) {
            $type = "select";
        }
        if (in_array('FETCH', $context)) {
            $type = "select";
        }

        if (in_array('SELECT', $context)) {
            $type = "select";
        }
        if (in_array('REPLACE', $context)) {
            $type = "replace";
        }

        if (in_array('DELETE', $context)) {
            $type = "delete";
        }



        if ($type === "from") {
            echo "unknown from\n";
            print_r($context);
            print_r($tree);
            die();
        }

        foreach ($tree as $item) {
            if(!isset($item['table'])){
                continue;
            }
            if(in_array(strtolower($item['table']),['inserted', 'deleted']))
            {
                continue;
            }

            $pack->add(
                new Link($root, $type, $item['table'])
            );


        }
        return $pack;
    }

}