<?php
namespace Gtd\VueEditor\Block;


class BlockConfig{

    /**
     * @var string
     */
    private string $title;

    /**
     * @var string
     */
    private ?string $type;

    private object $params;

    /**
     * BlockConfig constructor.
     * @param $json
     */
    public function __construct($json)
    {
        $this->setTitle($json->name);
        $this->setType($json->type);
        $this->setParams($json->conf);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type ? $this->type : "";
    }

    /**
     * @param string $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return string[]
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param string[] $params
     */
    public function setParams(\stdClass $params): void
    {
        $this->params = $params;
    }

}