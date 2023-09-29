<?php

namespace Analyser\Processor;

use Analyser\Links\Link;
use Analyser\Links\LinkPack;

class ExecProcessor extends AbstractProcessor
{

    public function process(array $tree, array $root, array $context): LinkPack
    {
        //echo "exec {$root['name']}->{$tree['name']}\n";

        return (new LinkPack())->add(new Link($root,'exec', $tree['name']));
    }
}