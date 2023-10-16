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

        $tableContext = new Context(new Root('table', $tree['table']),[]);
        return (new LinkPack())
            ->add(new Item('trigger', $tree['name']))
            ->add(new Link($tableContext, 'triggers', $tree['name']));
    }
}