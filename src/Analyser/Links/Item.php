<?php

namespace Analyser\Links;

class Item extends AbstractItem
{

    private $type, $name;

    private $context;

    /**
     * @param $type
     * @param $name
     */
    public function __construct($type, $name, Context $context)
    {
        if ($type == 'exec') {
            throw new \Exception("WTF?");
        }

        $this->type = trim($type);
        $this->name = trim($name);
        $this->context = $context;
    }


    public function generate(): string
    {
        if ($this->skipName($this->name)) {
            return "";
        }

        list($name, $sname) = $this->getNames($this->name,$this->context);

        switch ($this->type) {
            case "table" :
                return 'Table(' . $sname . ', ' . $name . ')';
            case "trigger" :
                return 'Trigger(' . $sname . ', ' . $name . ')';
            case "procedure" :
                return 'Proc(' . $sname . ', ' . $name . ')';
            case "function" :
                return 'Func(' . $sname . ', ' . $name . ')';
            case "declare":
                return 'Declare(' . $sname . ', ' . $name . ')';
            case "type":
                return 'Type(' . $sname . ', ' . $name . ')';
            case "syn":
                return 'Synonym(' . $sname . ', ' . $name . ')';
            case "view":
                return 'View(' . $sname . ', ' . $name . ')';
            default:
                throw new \Exception("unknown render type: {$this->type}");
        }
    }



    public function getName(): string
    {
        return $this->getNames($this->name,$this->context)[1];
    }
}