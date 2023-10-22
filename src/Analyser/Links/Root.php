<?php

namespace Analyser\Links;

class Root implements Contextable
{
    private $type, $name, $base;


    private $aliases = [];

    /**
     * @param string $type
     * @param string $name
     */
    public function __construct(string $type, string $name, $base)
    {
        $this->type = $type;
        $this->name = $name;
        $this->base = $base;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getBase()
    {
        return $this->base;
    }


    public function addAlias(string $alias, string $name)
    {
        if ($alias != $name) {
            $alias = AbstractItem::clear($alias);
            $this->aliases[$alias] = $name;
        }

        return $this;
    }

    public function resolve(string $alias): string
    {
        $l_alias = strtolower(str_replace(["[", "]"], "", $alias));
        if (isset($this->aliases[$l_alias])) {
            return $this->aliases[$l_alias];
        }
        return $alias;
    }

}