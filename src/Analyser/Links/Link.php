<?php

namespace Analyser\Links;

class Link extends AbstractItem
{

    private $root, $linkType, $to;


    /**
     * @param $root
     * @param $linkType
     * @param $to
     */
    public function __construct($root, $linkType, $to)
    {
        $this->root = $root;
        $this->root['name'] = trim($this->root['name']);
        $this->linkType = $linkType;
        $this->to = trim($to);
    }


    public function generate():string
    {
        $from = $this->getSysName($this->root['name']);
        $to = $this->getSysName($this->to);

        if(in_array($this->to[0],["@","#"])){
            return "";
        }

        switch($this->linkType){
            case 'trigger':
                // table triggers trigger. (link creation in scope of trigger)
                return "triggers({$to},{$from})";
            case 'insert':
            case 'replace':
            case 'delete':
            case 'exec':
            case 'select':
                return "uses({$from}, {$to})";
            default:
                throw new \Exception("unknown link type {$this->linkType}");
        }


    }
}