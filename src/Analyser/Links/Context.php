<?php

namespace Analyser\Links;

class Context implements Contextable
{
    private $root;

    private $aliases;

    private $blocks;

    /**
     * @param Root $root
     */
    public function __construct(Root $root, array $blocks)
    {
        $this->root = $root;
        $this->blocks = $blocks;
    }

    public function addAlias($alias, $name)
    {
        if ($alias != $name) {
            $alias = AbstractItem::clear($alias);
            $this->aliases[strtolower($alias)] = trim($name);
        }
        return $this;

    }

    public function getRoot(): Root
    {
        return $this->root;
    }

    public function resolve(string $alias): string
    {
        $l_alias = strtolower($alias);
        if (isset($this->aliases[$l_alias])) {
            return $this->aliases[$l_alias];
        }
        return $this->root->resolve($alias);
    }

    public function hasBlock($name): bool
    {
        return in_array($name, $this->blocks);
    }

}