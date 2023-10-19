<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\Contextable;
use Analyser\Links\Item;
use Analyser\Links\Link;
use Analyser\Links\LinkPack;
use Analyser\Links\Root;

class DeclareProcessor extends AbstractProcessor
{

    public function process(array $tree, Context $context): LinkPack
    {
        $out = new LinkPack();
        foreach ($tree['items'] as $item) {
            $name = $item['name'];
                $context->getRoot()->addAlias($name, $item['type']);
        }
        return $out;
    }
}