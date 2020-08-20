<?php
namespace Gtd\VueEditor\Block;

class DefaultHandler implements Handler{

    /**
     * @var Block;
     */
    private $block;

    public function getData()
    {
        return $this->block->getData();
    }

    public function setBlock(Block $block): void
    {
        $this->block = $block;
    }
}