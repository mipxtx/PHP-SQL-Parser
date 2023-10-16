<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\Link;
use Analyser\Links\LinkPack;

class DeleteProcessor extends AbstractProcessor
{

    public function process(array $tree, Context $context): LinkPack
    {

        $list = new LinkPack();
        if(isset($tree['tables']) && is_array($tree['tables'])){
            foreach ($tree['tables'] as $table){
                $list->add(new Link($context,'delete', $table));
            }
        }
        return $list;
    }
}