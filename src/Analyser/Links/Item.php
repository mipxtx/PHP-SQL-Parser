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
        $this->type = trim($type);
        $this->name = trim($name);
    }


    public function generate(): string
    {

        $sname = $this->getSysName($this->name);
        switch ($this->type) {
            case "table" :
                return 'Table(' . $sname . ', ' . $this->name . ')';
            case "trigger" :
                return 'Trigger(' . $sname . ', ' . $this->name . ') {}';
            case "procedure" :
                return 'Proc(' . $sname . ', ' . $this->name . ') {}';
            default:
                throw new \Exception("unknown render type: {$this->type}");
        }
    }
}