<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\Link;
use Analyser\Links\LinkPack;
use Analyser\Links\Root;

class FromProcessor extends AbstractProcessor
{

    public function process(array $tree, Context $context): LinkPack
    {
        $pack = new LinkPack();
        $type = "from";

        foreach (['SET', 'OPEN', 'FETCH', 'SELECT'] as $block) {
            if ($context->hasBlock($block)) {
                $type = 'select';
                break;
            }
        }

        if ($context->hasBlock('REPLACE')) {
            $type = "replace";
        }

        if ($context->hasBlock('DELETE')) {
            $type = "delete";
        }

        if($context->hasBlock('TYPE')){
            $type = "type";
        }

        if($context->hasBlock('RECEIVE')){
            $type = "receive";
        }

        if($context->hasBlock('CONVERSATION')){
            $type = "conversation";
        }

        if ($type === "from") {
            print_r($context);
            print_r($tree);
            throw new \Exception("unknown from");
        }

        foreach ($tree as $item) {

            if (!isset($item['table'])) {
                continue;
            }

            if (isset($item['alias']['name'])) {
                $context->addAlias(
                    $item['alias']['name'],
                    $item['table']
                );
            }

            $pack->add(
                new Link($context, $type, $item['table'])
            );

        }

        return $pack;
    }

}