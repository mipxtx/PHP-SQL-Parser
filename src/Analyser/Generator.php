<?php

namespace Analyser;

use Analyser\Links\AbstractItem;
use Analyser\Links\LinkPack;
use PHPSQLParser\Options;
use PHPSQLParser\PHPSQLParser;

class Generator
{
    private $input, $output;

    private $project;

    /**
     * @var PHPSQLParser
     */
    private $parser;


    private $exclude;
    /**
     * @param PHPSQLParser $parser
     */
    public function __construct($input, $output, $project, array $exclude = [])
    {
        $this->input = realpath($input . "/$project") . "/";
        if (!file_exists($this->input)) {
            throw new \Exception("{$input} not found");
        }


        $this->output = $output;
        if (!file_exists($this->output)) {
            mkdir($this->output);
        }
        $this->project = str_replace("-", "_", $project);
        $this->exclude = $exclude;

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
        $list = (new BaseAnalyser())->analyseTop($out, $this->project);
        print_r($list);
        print_r($list->render());
        //$this->renderFile($out,"","");
    }

    public function run($folder)
    {
        //echo "Folder: $folder\n";
        if(!file_exists($this->input . $folder)){
            return;
        }
        $listFile = str_replace("/", "_", $folder) . ".plantuml";

        if(!file_exists($this->output. "/{$this->project}/")){
            mkdir($this->output. "/{$this->project}/");
        }

        file_put_contents(
            $this->output. "/{$this->project}/" . $listFile,
            "@startuml\n!include ../DatabasePhysical.iuml\n"
        );

        $files = scandir($this->input . $folder);

        try {
            foreach ($files as $i => $file) {
                if ($file[0] == ".") {
                    continue;
                }
                if (in_array($file, $this->exclude)) {
                    continue;
                }
                echo "$i:$file\n";
                $list = $this->runFile($folder, $file);
                file_put_contents(
                    $this->output . "/{$this->project}/"."/$listFile",
                    implode("\n", $list) . "\n",
                    FILE_APPEND
                );
            }
        } catch (\Throwable $e) {
            throw $e;
        } finally {
            file_put_contents(
                $this->output . "/{$this->project}/$listFile",
                "@enduml",
                FILE_APPEND
            );
        }
    }

    public function runFile($folder, $file)
    {
        $text = $this->loadFile($this->input . "$folder/$file");
        $out = $this->parser->parse($text);
        $list = (new BaseAnalyser())->analyseTop($out, $this->project);
        return $this->renderFile($list);
    }

    public function renderFile(LinkPack $list)
    {
        $out = [];
        $render = $list->render();

        foreach ($render as $name => $text) {
            $ftext = "";
            $ftext .= "@startuml\n!include ../../DatabasePhysical.iuml\n";
            $ftext .= $text;
            $ftext .= "\n@enduml";

            $project = $this->project;
            if (substr_count($name, ".") == 3) {
                list($project) = explode(".", $name, 2);
            }

            if (!file_exists($this->output . "/{$project}/files/")) {
                mkdir($this->output . "/{$project}/files/",0777,true);
            }

            file_put_contents($this->output . "/{$project}/files/$name.plantuml", $ftext);
            $out[] = "!include ./files/{$name}.plantuml";
        }
        return $out;

    }


    public function loadFile($file)
    {
        $text = file_get_contents($file);
        if ($text === false) {
            echo $file . " not exist\n";
        }
        $bom = pack('H*','EFBBBF');
        return preg_replace("/^$bom/", '', $text);
    }

}