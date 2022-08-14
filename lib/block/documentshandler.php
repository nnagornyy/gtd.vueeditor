<?php
namespace Gtd\VueEditor\Block;

class DocumentsHandler implements Handler{

  /**
   * @var Block;
   */
  private $block;

  /**
   * @return array|mixed
   */
  public function getData()
  {
    $data = $this->block->getData();

    foreach ($data as $key => $item){
      $data[$key]['file']['id'] = (string)$item['file']['id'];
      $data[$key]['file']['fileSize'] = (string)$item['file']['fileSize'];
    }

    return $data;
  }

  /**
   * @param Block $block
   * @return void
   */
  public function setBlock(Block $block): void
  {
    $this->block = $block;
  }
}
