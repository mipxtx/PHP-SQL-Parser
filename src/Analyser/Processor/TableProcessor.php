<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\Item;
use Analyser\Links\LinkPack;
use Analyser\Links\Root;

class TableProcessor extends AbstractProcessor
{

    public function process(array $tree, Context $context): LinkPack
    {
        if ($context->hasBlock("CREATE")) {

            if($context->getRoot() && $tree['name'] != $context->getRoot()->getName()){
                $context->getRoot()->addAlias($tree['name'], "TABLE");
                return new LinkPack();
            }else {
                return (new LinkPack())->add(new Item('table', trim($tree['name']), $context));
            }
        }else{
            throw new \Exception('strange table');
        }
    }
}