<?php

namespace Analyser\Processor;

use Analyser\Links\LinkPack;

class BeginProcessor extends AbstractProcessor
{

    public function process(array $tree, array $root, array $context): LinkPack
    {
        return $this->analyse($tree['sub_tree'], $root, $context);
    }
}