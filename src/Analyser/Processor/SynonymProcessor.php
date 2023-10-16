<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\Item;
use Analyser\Links\Link;
use Analyser\Links\LinkPack;

class SynonymProcessor extends AbstractProcessor
{

    public function process(array $tree, Context $context): LinkPack
    {
        return (new LinkPack())
            ->add(new Item("syn", $tree['name']))
            ->add(new Link($context,"syn", $tree['target']));
    }
}