<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\LinkPack;

class WithProcessor  extends AbstractProcessor
{

    public function process(array $tree, Context $context): LinkPack
    {
        $pack = new LinkPack();
        foreach ($tree as $item){
            //echo $item['name'] . "\n";
            $context->getRoot()->addAlias($item['name'], "");
        }
        return $pack;
    }
}