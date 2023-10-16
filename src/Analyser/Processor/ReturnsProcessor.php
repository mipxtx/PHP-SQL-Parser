<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\LinkPack;

class ReturnsProcessor extends AbstractProcessor
{

    public function process(array $tree, Context $context): LinkPack
    {
        if(isset($tree['name'])){
            $context->getRoot()->addAlias($tree['name'],$tree['type']);
        }
        return new LinkPack();
    }
}