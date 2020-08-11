<?php
namespace Gtd\VueEditor\Block;

interface Handler {
    /**
     * return block data
     * @return array
     */
    public function getData(): array;

    public function setBlock(Block $block): void;
}