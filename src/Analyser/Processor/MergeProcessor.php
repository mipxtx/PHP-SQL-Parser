<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\Link;
use Analyser\Links\LinkPack;

class MergeProcessor extends AbstractProcessor
{

    public function process(array $tree, Context $context): LinkPack
    {

        if($tree["alias"]){
            $context->addAlias($tree["alias"], $tree["name"]);
        }
        return (new LinkPack())->add(new Link($context, 'update', $tree["name"]));
    }
}