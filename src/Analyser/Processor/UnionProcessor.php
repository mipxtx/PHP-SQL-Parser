<?php

namespace Analyser\Processor;

use Analyser\Links\LinkPack;

class UnionProcessor extends AbstractProcessor
{

    public function process(array $tree, array $root, array $context): LinkPack
    {
        echo "union";
        print_r($tree);
        die();
    }
}