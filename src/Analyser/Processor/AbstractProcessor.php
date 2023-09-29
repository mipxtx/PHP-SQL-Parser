<?php

namespace Analyser\Processor;

use Analyser\BaseAnalyser;
use Analyser\Links\AbstractItem;
use Analyser\Links\LinkPack;

abstract class AbstractProcessor
{
    /**
     * @var BaseAnalyser
     */
    protected $base;

    public function __construct(BaseAnalyser $ba)
    {
        $this->base = $ba;
    }

    public function analyse($tree, $root,$context): LinkPack
    {
        return $this->base->analyse($tree, $root, $context);
    }

    public function printDepts(array $array, $depth, $start)
    {
        $dist = $start - $depth;

        echo "\n" . $this->printDist($dist) . "[";
        if ($depth != 0) {
            foreach ($array as $key => $value) {
                echo "\n" . $this->printDist($dist + 1) . "$key =>";
                if (is_array($value)) {
                    echo "\n" . $this->printDepts($value, $depth - 1, $start);
                } else {

                }
            }
        }
        echo $this->printDist($dist) . "]";
    }

    public function printDist($size)
    {
        $out = "";
        for ($i = 0; $i < $size; $i++) {
            $out .= " ";
        }
        return $out;
    }

    public abstract function process(array $tree, array $root, array $context): LinkPack;

    public function getSysName($sname){
        return AbstractItem::sysName($sname);
    }
}