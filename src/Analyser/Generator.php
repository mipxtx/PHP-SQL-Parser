<?php

namespace Analyser;

use Analyser\Links\AbstractItem;
use Analyser\Links\LinkPack;
use PHPSQLParser\Options;
use PHPSQLParser\PHPSQLParser;

class Generator
{
    private $input, $output;

    /**
     * @var PHPSQLParser
     */
    private $parser;

    /**
     * @param PHPSQLParser $parser
     */
    public function __construct($input, $output)
    {
        $this->input = realpath($input);
        if (!file_exists($this->input)) {
            throw new \Exception("{$this->input} not found");
        }
        $this->output = $output;

        $this->parser = new PHPSQLParser(false, false, [Options::QUERY_DELIMITER => "GO"]);
    }


    public function _run()
    {

        $file = "test.sql";
        $text = $this->loadFile($file);

        $out = $this->parser->parse($text);
        //mprint_r($out,null,['base_expr']);
        //die();

        //die();
        $list = (new BaseAnalyser())->analyseTop($out);
        print_r($list);
        //$this->renderFile($out,"","");

    }

    public function run()
    {
        $folder = "/Stored Procedures";

        file_put_contents($this->output . "$folder.plantuml", "");

        if (!file_exists($this->output . $folder)) {
            mkdir($this->output . $folder);
        }

        $files = scandir($this->input . $folder);
        array_shift($files);
        array_shift($files);

        foreach ($files as $i => $file) {
            echo "$i:$file\n";
            $this->runFile($folder, $file);
        }

    }

    public function runFile($folder, $file)
    {
        $text = $this->loadFile($this->input . "$folder/$file");
        $out = $this->parser->parse($text);
        $list = (new BaseAnalyser())->analyseTop($out);

        $this->renderFile($list, $folder, $file);
    }

    public function renderFile(LinkPack $list, $folder, $file)
    {
        $path = explode(".", $file);
        array_pop($path);
        array_push($path, "plantuml");
        $filename = implode(".", $path);
        $filename = AbstractItem::sysName($filename);


        //echo "writing file ".$this->output . $filename. "\n";
        file_put_contents($this->output . $folder . "/" . $filename, $list->render());

        file_put_contents($this->output . "$folder.plantuml", "!include .{$folder}/{$filename}\n", FILE_APPEND);
    }


    public function loadFile($file)
    {
        $text = file_get_contents($file);

        //echo $text . "\n--------\n";
        $lines = explode("\n", $text);
        foreach ($lines as &$line) {
            list($line) = explode("--", $line, 2);
        }
        $text = implode("\n", $lines);

        //preg_match_all("/(\/\*.*?\*\/)/s",$text,$out);

        //print_r($out);

        $text = preg_replace("/(\/\*.*?\*\/)/s", "", $text);
        //echo $text . "\n";
        //die();
        return $text;
    }

}