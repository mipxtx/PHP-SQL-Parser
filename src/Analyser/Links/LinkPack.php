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

    public function render(): array
    {
        $out = [];
        foreach ($this->items as $item) {
            $str = $item->generate();
            if ($str) {
                $out[$item->getName()][] = $str;
            }
        }
        $res = [];
        foreach ($out as $name => $pack) {
            $pack = array_unique($pack);
            $res[$name] = implode("\n", $pack);
        }
        return $res;
    }
}