<?php

namespace Analyser\Links;

class LinkPack
{
    /**
     * @var AbstractItem[]
     */
    private $items = [];

    public function add(AbstractItem $item)
    {
        $this->items[] = $item;
        return $this;
    }

    public function merge(LinkPack $l)
    {
        $items = array_merge($this->items, $l->items);
        $res = new self();
        foreach ($items as $item) {
            $res->add($item);
        }
        return $res;
    }

    public function render(){
        $out = [];
        foreach ($this->items as $item){
            $out[] = $item->generate();
        }

        $out = array_unique($out);
        return implode("\n",$out);
    }
}