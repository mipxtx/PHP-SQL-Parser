<?php

namespace Analyser\Processor;

use Analyser\Links\Link;
use Analyser\Links\LinkPack;

class UpdateProcessor extends AbstractProcessor
{

    public function process(array $tree, array $root, array $context): LinkPack
    {

        $pack = new LinkPack();

        //print_r($tree);

        $from = $this->getSysName($root['name']);


        foreach ($tree as $item) {
            //echo "update: {$root['name']}->" . $item['table'] . "\n";
            //$to = $this->getSysName($item['table']);
            //echo "uses($from,$to)\n";
            $pack->add(new Link($root, 'insert', $item['table']));
        }
        return $pack;
    }
}