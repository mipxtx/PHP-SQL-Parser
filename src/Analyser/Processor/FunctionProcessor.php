<?php

namespace Analyser\Processor;

use Analyser\Links\Item;
use Analyser\Links\LinkPack;

class FunctionProcessor extends AbstractProcessor
{
    public function process(array $tree, array $root, array $context): LinkPack
    {
        echo "function:  " . $tree['name'] . "\n";
        return (new LinkPack())->add(new Item('exec', $tree['name']));
    }
}