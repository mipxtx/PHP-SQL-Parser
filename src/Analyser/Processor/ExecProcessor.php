<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\Link;
use Analyser\Links\LinkPack;
use Analyser\Links\Root;

class ExecProcessor extends AbstractProcessor
{

    public function process(array $tree, Context $context): LinkPack
    {

        if(!isset($tree['name'])){
            return new LinkPack();
        }

        //echo "exec {$root['name']}->{$tree['name']}\n";

        return (new LinkPack())->add(new Link($context,'exec', $tree['name']));
    }
}