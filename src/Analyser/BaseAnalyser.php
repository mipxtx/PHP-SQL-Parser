<?php

namespace Analyser;

use Analyser\Links\LinkPack;
use Analyser\Processor\AbstractProcessor;
use Analyser\Processor\ExecProcessor;
use Analyser\Processor\FromProcessor;
use Analyser\Processor\FunctionProcessor;
use Analyser\Processor\IfProcessor;
use Analyser\Processor\IntoProcessor;
use Analyser\Processor\ProcedureProcessor;
use Analyser\Processor\TableProcessor;
use Analyser\Processor\TriggerProcessor;
use Analyser\Processor\UnionProcessor;
use Analyser\Processor\UpdateProcessor;
use Analyser\Processor\BeginProcessor;
use Analyser\Processor\WithProcessor;

class BaseAnalyser
{

    private $debug = false;
    const ROOT_MAP = [
        'TABLE' => 'table',
        'PROCEDURE' => 'proc',
        'FUNCTION' => 'func',
        'TRIGGER' => 'trig'
    ];

    const PROCESSOR_MAP = [
        'PROCEDURE' => ProcedureProcessor::class,
        'FUNCTION' => FunctionProcessor::class,
        'FROM' => FromProcessor::class,
        'EXEC' => ExecProcessor::class,
        'INTO' => IntoProcessor::class,
        'UPDATE' => UpdateProcessor::class,
        'IF' => IfProcessor::class,
        'TABLE' => TableProcessor::class,
        'TRIGGER' => TriggerProcessor::class,
        //'UNION' => UnionProcessor::class,
        //'WITH' => WithProcessor::class,
    ];


    public function analyseTop($in): LinkPack
    {
        $out = new LinkPack();
        foreach ($in as $item) {
            $root = [];
            foreach (self::ROOT_MAP as $key => $type) {
                if (isset($item[$key])) {
                    $root = ['type' => $type, 'name' => $item[$key]['name']];
                    break;
                }
            }
            if ($root) {
                $out = $out->merge($this->analyse([$item], $root));
            }
        }
        return $out;
    }

    public function analyse(array $tree, $root): LinkPack
    {
        $out = new LinkPack();

        if($this->debug) {
            echo "in:\n";
            mprint_r($tree, 3, ['base_expr']);
        }

        foreach ($tree as $i => $item) {
            if($this->debug)
                echo "iterate item $i\n";
                if(isset($item['expr_type']) || isset($item['base_expr'])){
                    if (isset($item['sub_tree']) && is_array($item['sub_tree'])) {
                        if($this->debug)
                            echo "jump in sub_tree\n";
                        $out = $out->merge($this->analyse($item['sub_tree'], $root));
                        if($this->debug)
                            echo "jump out sub_tree\n";
                    }
                }else {
                    foreach (self::PROCESSOR_MAP as $name => $class) {
                        if (isset($item[$name])) {
                            $context = array_keys($item);
                            /** @var AbstractProcessor $processor */
                            $processor = new $class($this);
                            $out = $out->merge($processor->process($item[$name], $root, $context));
                        }
                    }
                    if(!is_array($item)){

                        var_dump($item);
                        print_r($tree);

                        throw new \Exception("unknown behavior");
                    }
                    foreach ($item as $key => $pack) {
                        if($this->debug)
                            echo "iterate $key\n";
                        if (isset($pack[0])) {
                            if($this->debug)
                                echo "jump in $key\n";
                            $out = $out->merge($this->analyse($pack, $root));
                            if($this->debug)
                                echo "jump out $key\n";
                        } elseif (isset($pack['expr_type']) || isset($pack['sub_tree'])|| isset($item['base_expr'])) {
                            if (isset($pack['sub_tree']) && is_array($pack['sub_tree'])) {
                                if($this->debug)
                                    echo "jump in sub_tree\n";
                                $out = $out->merge($this->analyse($pack['sub_tree'], $root));
                                if($this->debug)
                                    echo "jump out sub_tree\n";
                            }
                        } else {
                            if($this->debug) {
                                echo "unknown pack in $key:";
                                mprint_r($pack, 4, ['base_expr']);
                            }
                            //die();
                        }
                    }
                }
            }



        return $out;
    }
}