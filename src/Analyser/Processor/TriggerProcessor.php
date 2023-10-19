<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\Item;
use Analyser\Links\Link;
use Analyser\Links\LinkPack;
use Analyser\Links\Root;

class TriggerProcessor extends AbstractProcessor
{
    public function process(array $tree, Context $context): LinkPack
    {
        $tableContext = new Context(new Root('table', $tree['table'], $context->getRoot()->getBase()),[]);
        return (new LinkPack())
            ->add(new Item('trigger', $tree['name'], $context))
            ->add(new Link($tableContext, 'triggers', $tree['name']));
    }
}