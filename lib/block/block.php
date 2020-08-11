<?php
namespace Gtd\VueEditor\Block;

use JsonSerializable;

class Block implements JsonSerializable{

    private $title;

    private $type;

    private $data;


    public function __construct($array)
    {
        $this->setTitle($array['title']);
        $this->setType($array['type']);
        $this->setData($array['data']);
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'title' => $this->getTitle(),
            'type' => $this->getType(),
            'data' => $this->getData()
        ];
    }
}
