<?php

namespace Analyser\Processor;

use Analyser\Links\LinkPack;

class WithProcessor extends AbstractProcessor
{

    public function process(array $tree, array $root, array $context): LinkPack
    {
        $out = new LinkPack();
        echo "with\n";
        print_r($tree);
        die();
    }
}