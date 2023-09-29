<?php

namespace Analyser\Processor;

use Analyser\Links\Item;
use Analyser\Links\Link;
use Analyser\Links\LinkPack;

class TriggerProcessor extends AbstractProcessor
{

    public function process(array $tree, array $root, array $context): LinkPack
    {
        return (new LinkPack())
            ->add(new Item('trigger', $tree['name']))
            ->add(new Link(['type' => 'trigger', 'name' => $tree['name']], 'trigger', $tree['table']));
    }
}