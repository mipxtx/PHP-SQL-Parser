<?php

namespace Analyser\Links;

class Link extends AbstractItem
{
    private $context, $linkType, $to;

    /**
     * @param $root
     * @param $linkType
     * @param $to
     */
    public function __construct(Context $context, $linkType, $to)
    {
        $this->context = $context;
        $this->linkType = $linkType;
        $this->to = trim($to);
    }


    public function getFrom()
    {
        return $this->context->getRoot()->getName();
    }



    public function generate(): string
    {
        $from = $this->getSysName($this->getFrom());

        list($_, $from) = $this->getNames($from, $this->context);

        $to = $this->context->resolve($this->to);

        $to = $this->context->resolve($to);

        if ($this->skipName($to)) {
            return "";
        }

        list($_, $to) = $this->getNames($to, $this->context);

        switch ($this->linkType) {
            case 'triggers':
            case 'insert':
            case 'replace':
            case 'update':
            case 'delete':
            case 'select':
            case 'exec':
            case 'syn' :
            case 'receive' :
            case 'conversation' :
            case 'dialog' :
//            case 'declare':
                return "{$this->linkType}({$from}, {$to})";
            case "type" :
                return "";
            default:
                throw new \Exception("unknown link type {$this->linkType}");
        }
    }

    public function getName(): string
    {
        return $this->getNames($this->getFrom(),$this->context)[1];
    }
}