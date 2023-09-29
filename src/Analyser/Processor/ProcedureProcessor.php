<?php

namespace Analyser\Processor;

use Analyser\AnalyserInterface;
use Analyser\Links\Item;
use Analyser\Links\Link;
use Analyser\Links\LinkPack;

class ProcedureProcessor extends AbstractProcessor
{
    public function process(array $tree, array $root, array $context): LinkPack
    {

        //$alias = $this->getSysName($tree['name']);
        //echo "Proc($alias, {$tree['name']})\n";
        return (new LinkPack())->add(new Item('procedure', $tree['name']));
    }
}