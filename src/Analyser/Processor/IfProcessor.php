<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\LinkPack;

class IfProcessor extends AbstractProcessor
{

    public function process(array $tree, Context $context): LinkPack
    {
        $out = new LinkPack();
        foreach ($tree as $item) {
            if (isset($item['sub_tree']) && is_array($item['sub_tree'])) {
                $out = $out->merge($this->analyse($item['sub_tree'],$context));
            }
        }
        return $out;
    }
}