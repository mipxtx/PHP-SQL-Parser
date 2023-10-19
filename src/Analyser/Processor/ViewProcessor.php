<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\Item;
use Analyser\Links\LinkPack;

class ViewProcessor extends AbstractProcessor
{

    public function process(array $tree, Context $context): LinkPack
    {
        return (new LinkPack())->add(new Item('view', $tree['name'], $context));
    }
}