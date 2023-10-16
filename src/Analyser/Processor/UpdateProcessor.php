<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\Link;
use Analyser\Links\LinkPack;
use Analyser\Links\Root;

class UpdateProcessor extends AbstractProcessor
{

    public function process(array $tree,Context $context): LinkPack
    {

        $pack = new LinkPack();

        //print_r($tree);

        //$from = $this->getSysName($root['name']);


        foreach ($tree as $item) {
            //echo "update: {$root['name']}->" . $item['table'] . "\n";
            //$to = $this->getSysName($item['table']);
            //echo "uses($from,$to)\n";
            $pack->add(new Link($context, 'update', $item['table']));
        }
        return $pack;
    }
}