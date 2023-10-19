<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\Item;
use Analyser\Links\LinkPack;
use Analyser\Links\Root;

class FunctionProcessor extends AbstractProcessor
{
    public function process(array $tree, Context $context): LinkPack
    {
        //echo "function:  " . $tree['name'] . "\n";
        return (new LinkPack())->add(new Item('function', $tree['name'],$context));
    }
}