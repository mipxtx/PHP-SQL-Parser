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
        $this->input = realpath($input) . "/";
        if (!file_exists($this->input)) {
            throw new \Exception("{$input} not found");
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
        print_r($list->render());
        //$this->renderFile($out,"","");
    }

    public function run($folder)
    {
        echo "Folder: $folder\n";
        $listFile = str_replace("/","_",$folder) . ".plantuml";

        file_put_contents(
            $this->output . $listFile,
            "@startuml\n!include ../DatabasePhysical.iuml\n"
        );

        if (!file_exists($this->output ."/files")) {
            mkdir($this->output ."/files");
        }

        $files = scandir($this->input . $folder);

        try {
            foreach ($files as $i => $file) {
                if($file[0] == "."){
                    continue;
                }
                echo "$i:$file\n";
                $list = $this->runFile($folder, $file);
                file_put_contents(
                    $this->output . "/$listFile",
                    implode("\n",$list) . "\n",
                    FILE_APPEND
                );
            }
        } catch (\Throwable $e) {
            throw $e;
        } finally {
            file_put_contents(
                $this->output . "/$listFile",
                "@enduml",
                FILE_APPEND
            );
        }
    }

    public function runFile($folder, $file)
    {
        $text = $this->loadFile($this->input . "$folder/$file");
        $out = $this->parser->parse($text);
        $list = (new BaseAnalyser())->analyseTop($out);
        return  $this->renderFile($list, $folder, $file);
    }

    public function renderFile(LinkPack $list, $folder, $file)
    {

        $out = [];
        $render = $list->render();
        foreach ($render as $name => $text){
            $ftext = "";
            $ftext .= "@startuml\n!include ../../DatabasePhysical.iuml\n";
            $ftext .= $text;
            $ftext .= "\n@enduml";
            file_put_contents($this->output . "/files/$name.plantuml", $ftext);
            $out[] = "!include ./files/{$name}.plantuml";
        }
        return $out;

    }


    public function loadFile($file)
    {
        $text = file_get_contents($file);
        if($text === false){
            echo $file . " not exist\n";
        }
        return $text;
    }

}