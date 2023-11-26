<?php

namespace Analyser;

use Analyser\Links\AbstractItem;

class Finder
{

    const PART_SKIP = [
        "debug.execution_start",
        "debug.execution_finish",
        "debug.execution_point",


    ];
    const SKIP = [
        "logging",
        "deleted",
        'inserted',
        "cursor_local_for",
        'string_split',
        'openjson',
        "sysname",
        'tinyint',
        'int',
        'bigint',
        'numeric',
        'nvarchar',
        'datetime',
        'geography',
        'real',
        'table',
        'varchar',
    ];
    private $folder;

    private $items = [];

    private $links = [];

    private $reverse = [];

    private $not_found = [];

    /**
     * @param $folder
     */
    public function __construct($folder)
    {
        $this->folder = $folder;
    }

    public function run($name)
    {
        $name = AbstractItem::sysName($name);
        $this->runLink($name);
        $this->filter();
        return $this->write();
    }


    public function runLink($name)
    {

        if(substr_count($name, ".") == 2) {
            list($folder, $_) = explode(".", $name, 2);
            $file = "$folder/files/$name.plantuml";
            //echo "parsing {$this->folder}$file\n";
            if (!file_exists($this->folder . $file)) {
                $this->not_found[] = $name;
                return;
            }
            $text = file_get_contents($this->folder . $file);
            $deps = $this->umlParser($text);

            $this->items = array_merge($this->items, $deps['items']);
            $this->links = array_merge($this->links, $deps['links']);
            $this->reverse = array_merge($this->reverse, $deps['reverse']);

            foreach ($deps['links'] as $pack) {
                foreach ($pack as $item) {
                    $to = $item[1];
                    if (!isset($this->items[$to])) {
                        if (!in_array($name, $this->not_found)) {
                            $this->runLink($to);
                        }
                    }
                }
            }
        }
    }

    public function umlParser($text)
    {
        $out = ['items' => [], 'links' => [], 'reverse' => []];

        $lines = explode("\n", $text);
        array_shift($lines);
        array_shift($lines);
        array_pop($lines);
        foreach ($lines as $line) {

            if (!trim($line)) {
                continue;
            }
            list($type, $line) = explode("(", $line, 2);
            list($from, $line) = explode(",", $line, 2);
            $rr = explode(")", $line);
            array_pop($rr);
            $to = implode(")", $rr);

            $name = trim($to);
            switch ($type) {
                case "Proc" :
                case "Table" :
                case "Func" :
                case "Trigger" :
                case "Synonym" :
                case "Type" :
                case "View" :
                    $out['items'][$from] = [$type, $name];
                    break;
                case "select":
                case "insert":
                case "delete":
                case "update":
                case "replace":
                case "triggers":
                case "exec":
                case "uses":
                case "syn" :
                    $out['links'][$from][] = [$type, $name];
                    $out['reverse'][$name][] = [$type, $from];
                    break;
                default:
                    throw new Exception("unknownn type: $type");
            }
        }
        return $out;
    }

    public function filter()
    {
        foreach ($this->links as $from => $links) {
            foreach ($links as $i => $link) {
                $to = $link[1];


                foreach (self::PART_SKIP as $item){
                    if(strpos($to, $item) !== false){
                        unset($this->links[$from][$i]);
                        continue 2;
                    }
                }
                if (in_array($to, self::SKIP)) {
                    unset($this->links[$from][$i]);
                    continue;
                }
                if (isset($this->items[$to])) {
                    switch ($this->items[$to][0]) {
                        case "Proc":
                        case "Table":
                        case "Function":
                        case "Func":
                        case "Trigger":
                        case "View":
                        case "Type":
                            break;
                        case "Synonym" :
                            if (isset($this->links[$to][0][1])) {
                                $target = $this->links[$to][0][1];
                                $this->links[$from][$i][1] = $target;
                                unset($this->links[$to]);
                            }
                            break;
                        default:
                            throw new \Exception("unknown item {$this->items[$to][0]}");
                    }
                }
            }
        }
        foreach ($this->items as $name => $item) {
            if ($item[0] == "Synonym") {
                unset($this->items[$name]);
            }
        }
    }

    public function write()
    {
        $text = "@startuml\n!include ./DatabasePhysical.iuml\nleft to right direction\n";


        foreach ($this->items as $name => $pack) {
            $text .= "{$pack[0]}($name, {$pack[1]})\n";

        }
        foreach ($this->links as $from => $pack) {
            foreach ($pack as list($type, $to)) {
                switch ($type) {
                    case "insert":
                    case "update":
                    case "delete":
                    case "replace":
                        $type = "modify";
                        break;
                }
                $text .= "$type($from,$to)\n";
            }
        }


        $text .= "@enduml";
        return $text;
        //print_r($this->items);
        //print_r($this->links);
        //print_r($this->found);
        //echo "not found ";
        //print_r($this->not_found);
    }
}