<?php

namespace PHPSQLParser\processors;

class ProcedureProcessor extends AbstractProcessor
{

    public function parseArgs($tokens)
    {
        $argv = [];
        $argc = 0;

        foreach ($tokens as $i => $token) {
            if (!trim($token)) {
                continue;
            }

            if ($token == ",") {
                $argc++;
            } else {
                if (!isset($argv[$argc]) && $token[0] == "(") {
                    $argv = $this->parseArgs(
                        $this->splitSQLIntoTokens($this->removeParenthesisFromStart($token))
                    );
                    break;
                }
                if (strtoupper($token) == "AS") {
                    if (!isset($argv[$argc])) {
                        break;
                    } else {
                        continue;
                    }
                }

                $argv[$argc][] = $token;
            }
        }
        return $argv;
    }

    public function process($tokens)
    {
        $name = "";
        $args = false;
        $argvals = [];

        foreach ($tokens as $i => $token) {
            if (!trim($token)) {
                continue;
            }

            if (!$args) {
                $args = true;
                $name = $token;
            } else {
                $argvals = $this->parseArgs(array_slice($tokens, $i));
                break;
            }
        }


        $result = array();
        $result['base_expr'] = implode($tokens);


        $result['name'] = $name;
        $result['args'] = [];

        foreach ($argvals as $arg) {


            if (!isset($arg[1])) {
                print_r($tokens);
                print_r($arg);
                throw new \Exception("error parsing procedure");

            }

            $result['args'][] = ['name' => $arg[0], 'type' => $arg[1]];
        }

        return $result;
    }
}