<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\Item;
use Analyser\Links\LinkPack;

class TypeProcessor extends AbstractProcessor
{

    public function process(array $tree, Context $context): LinkPack
    {
        if (!isset($tree['name'])) {
            return new LinkPack();
        }

        return (new LinkPack())->add(new Item("type", $tree['name']));
    }
}