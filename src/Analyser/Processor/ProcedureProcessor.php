<?php

namespace Analyser\Processor;

use Analyser\AnalyserInterface;
use Analyser\Links\Context;
use Analyser\Links\Item;
use Analyser\Links\Link;
use Analyser\Links\LinkPack;
use Analyser\Links\Root;

class ProcedureProcessor extends AbstractProcessor
{
    public function process(array $tree, Context $context): LinkPack
    {

        foreach ($tree['args'] as $item){
            $context->getRoot()->addAlias($item['name'], $item['type']);
        }

        //$alias = $this->getSysName($tree['name']);
        //echo "Proc($alias, {$tree['name']})\n";
        return (new LinkPack())->add(new Item('procedure', $tree['name'],$context));
    }
}