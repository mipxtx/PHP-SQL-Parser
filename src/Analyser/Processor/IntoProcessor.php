<?php

namespace Analyser\Processor;

use Analyser\Links\Context;
use Analyser\Links\Link;
use Analyser\Links\LinkPack;
use Analyser\Links\Root;

class IntoProcessor extends AbstractProcessor
{

    public function process(array $tree, Context $context): LinkPack
    {
        return (new LinkPack())->add(
            new Link($context,'insert', $tree['table'])
        );
    }
}