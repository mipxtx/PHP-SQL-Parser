<?php

namespace Analyser\Links;

interface Contextable
{
    public function resolve(string $alias): string;
}