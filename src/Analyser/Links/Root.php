<?php

namespace Analyser\Links;

class Root implements Contextable
{
    private $type, $name;


    private $aliases = [];

    /**
     * @param string $type
     * @param string $name
     */
    public function __construct(string $type, string $name)
    {
        $this->type = $type;
        $this->name = $name;
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
        if (isset($this->aliases[$alias])) {
            return $this->aliases[$alias];
        }
        return $alias;
    }

}