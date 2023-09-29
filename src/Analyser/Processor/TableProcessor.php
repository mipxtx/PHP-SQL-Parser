<?php

namespace Analyser\Processor;

use Analyser\Links\Item;
use Analyser\Links\LinkPack;

class TableProcessor extends AbstractProcessor
{

    public function process(array $tree, array $root, array $context): LinkPack
    {
        if (in_array("CREATE", $context)) {
            //echo "table: " . $tree['name'] . "\n";
            return (new LinkPack())->add(new Item('table', trim($tree['name'])));
        }else{
            throw new \Exception('strange table');
        }
    }
}