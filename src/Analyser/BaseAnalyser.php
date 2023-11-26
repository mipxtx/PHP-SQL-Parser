<?php

namespace Analyser;

use Analyser\Links\Context;
use Analyser\Links\Contextable;
use Analyser\Links\LinkPack;
use Analyser\Links\Root;
use Analyser\Processor\AbstractProcessor;
use Analyser\Processor\DeclareProcessor;
use Analyser\Processor\DeleteProcessor;
use Analyser\Processor\ExecProcessor;
use Analyser\Processor\FromProcessor;
use Analyser\Processor\FunctionProcessor;
use Analyser\Processor\IfProcessor;
use Analyser\Processor\IntoProcessor;
use Analyser\Processor\MergeProcessor;
use Analyser\Processor\ProcedureProcessor;
use Analyser\Processor\ReturnsProcessor;
use Analyser\Processor\SynonymProcessor;
use Analyser\Processor\TableProcessor;
use Analyser\Processor\TriggerProcessor;
use Analyser\Processor\TypeProcessor;
use Analyser\Processor\UpdateProcessor;
use Analyser\Processor\ViewProcessor;
use Analyser\Processor\WithProcessor;

class BaseAnalyser
{

    private $debug = 0;
    const ROOT_MAP = [
        'TABLE' => 'table',
        'PROCEDURE' => 'proc',
        'FUNCTION' => 'func',
        'TRIGGER' => 'trig',
        'SYNONYM' => 'syn',
        'TYPE' => 'type',
        'VIEW' => 'view',
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
        'DECLARE' => DeclareProcessor::class,
        'WITH' => WithProcessor::class,
        "SYNONYM" => SynonymProcessor::class,
        "TYPE" => TypeProcessor::class,
        "DELETE" => DeleteProcessor::class,
        "RETURNS" => ReturnsProcessor::class,
        "VIEW" => ViewProcessor::class,
        "MERGE" => MergeProcessor::class,
    ];


    public function analyseTop($in, $project): LinkPack
    {

        $out = new LinkPack();
        foreach ($in as $pack) {
            $root = null;
            foreach ($pack as $item) {
                foreach (self::ROOT_MAP as $key => $type) {
                    if (isset($item[$key]) && $root === null) {
                        $root = new Root($type, $item[$key]['name'], $project);
                        break;
                    }
                }
                if ($root) {
                    $out = $out->merge($this->analyse([$item], $root));
                }
            }
        }
        return $out;
    }

    public function analyse(array $tree, Contextable $context): LinkPack
    {
        $out = new LinkPack();

        if ($this->debug) {
            echo "in:\n";
            mprint_r($tree, 3, ['base_expr']);
        }

        if (isset($tree['expr_type']) || isset($tree['base_expr']) || isset($tree['delim'])) {
            if (isset($tree['sub_tree']) && is_array($tree['sub_tree'])) {
                if ($this->debug)
                    echo "jump in sub_tree\n";
                $out = $out->merge($this->analyse($tree['sub_tree'], $context));
                if ($this->debug)
                    echo "jump out sub_tree\n";
            }
        } elseif (isset($tree[0])) {
            foreach ($tree as $i => $item) {
                if($this->debug) {
                    echo "iterate $i\n";
                }
                if(!is_array($item)){
                    //mprint_r($tree,null,['base_expr']);
                    print_r($context);
                    echo "tree: ";
                    var_dump($tree);

                    throw new \Exception("not an array");
                }
                $out = $out->merge($this->analyse($item, $context));
            }
        } else {
            if ($context instanceof Context) {
                $root = $context->getRoot();
            } elseif ($context instanceof Root) {
                $root = $context;
            } else {
                throw new \Exception("unknown context");
            }
            $context_keys = array_keys($tree);
            $l_context = new Context($root, $context_keys);

            foreach (self::PROCESSOR_MAP as $name => $class) {
                if (isset($tree[$name])) {
                    if ($this->debug)
                        echo "process $name\n";
                    /** @var AbstractProcessor $processor */
                    $processor = new $class($this);
                    $out = $out->merge($processor->process($tree[$name], $l_context));
                }
            }

            foreach ($tree as $key => $item) {
                if($this->debug){
                    echo "jump into $key\n";
                }

                if(!is_array($item)){
                    echo "not an array\ntree:";
                    print_r($tree);
                    throw new \Exception("not an array");
                }

                $out = $out->merge($this->analyse($item, $l_context));
                if($this->debug){
                    echo "jump out $key\n";
                }
            }
        }
        return $out;
    }
}