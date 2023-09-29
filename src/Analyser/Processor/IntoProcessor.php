<?php

namespace Analyser\Processor;

use Analyser\Links\Link;
use Analyser\Links\LinkPack;

class IntoProcessor extends AbstractProcessor
{

    public function process(array $tree, array $root, array $context): LinkPack
    {
        //echo "insert {$root['name']}({$root['type']})->" . $tree['table'] . "\n";


        //$tname = $this->getSysName($tree['table']);
        //$sname = $this->getSysName($root['name']);
        //echo "uses({$sname}, {$tname})\n";
        return (new LinkPack())->add(new Link($root,'insert', $tree['table']));
    }
}