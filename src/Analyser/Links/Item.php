<?php

namespace Analyser\Links;

class Item extends AbstractItem
{

    private $type, $name;


    /**
     * @param $type
     * @param $name
     */
    public function __construct($type, $name)
    {
        if($type=='exec'){
            throw new \Exception("WTF?");
        }

        $this->type = trim($type);
        $this->name = trim($name);
    }



    public function generate(): string
    {
        if($this->skipName($this->name)){
            return "";
        }

        $sname = $this->getSysName($this->name);
        switch ($this->type) {
            case "table" :
                return 'Table(' . $sname . ', ' . $this->name . ')';
            case "trigger" :
                return 'Trigger(' . $sname . ', ' . $this->name . ')';
            case "procedure" :
                return 'Proc(' . $sname . ', ' . $this->name . ')';
            case "function" :
                return 'Func(' . $sname . ', ' . $this->name . ')';
            case "declare":
                return 'Declare(' . $sname . ', ' . $this->name . ')';
            case "type":
                return 'Type(' . $sname . ', ' . $this->name . ')';
            case "syn":
                return 'Synonym(' . $sname . ', ' . $this->name . ')';
            default:
                throw new \Exception("unknown render type: {$this->type}");
        }
    }

    public function getName(): string
    {
        return $this->getSysName($this->name);
    }
}