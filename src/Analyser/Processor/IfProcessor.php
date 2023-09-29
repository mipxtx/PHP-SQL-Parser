<?php

namespace Analyser\Processor;

use Analyser\Links\LinkPack;

class IfProcessor extends AbstractProcessor
{

    public function process(array $tree, array $root, array $context): LinkPack
    {
        $out = new LinkPack();
        foreach ($tree as $item) {
            if (isset($item['sub_tree']) && is_array($item['sub_tree'])) {
                $out = $out->merge($this->analyse($item['sub_tree'],$root,$context));
            }
        }
        return $out;
    }
}