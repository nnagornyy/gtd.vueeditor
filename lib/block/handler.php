<?php
namespace Gtd\VueEditor\Block;

interface Handler {
    /**
     * return block data
     * @return array
     */
    public function getData();

    public function setBlock(Block $block): void;
}