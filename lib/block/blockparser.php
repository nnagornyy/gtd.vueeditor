<?php
namespace Gtd\VueEditor\Block;


class BlockParser {

    /**
     * @var BlockConfig []
     */
    private array $configs;

    /**
     * @var Block []
     */
    private $blocks = [];

    public function __construct($arDbBlock){
        $this->loadBlockConfigs();
        $this->setBlocks($arDbBlock);
    }

    private function loadBlockConfigs(){
        $finder = new Finder();
        $configs = $finder->find('all');
        foreach ($configs as $config){
            $this->configs[$config->getType()] = $config;
        }
    }

    private function setBlocks($array){
        foreach ($array as $dbBlock){
            $this->blocks[] = new Block($dbBlock);
        }
    }

    public function processingBlock(){
        $result = [];
        foreach ($this->blocks as $block){
            $resultBlock = [];
            $handler = $this->getBlockHandler($block->getType());
            $handler->setBlock($block);
            $resultBlock['type'] = $block->getType();
            $resultBlock['content'] = $handler->getData();
            $result[] = $resultBlock;
        }
        return $result;
    }

    private function getBlockHandler($type): Handler
    {
        $config = $this->getConfigByType($type);
        $handlerClass = $config->getHandler();
        if(!empty($handlerClass)){
            if(!class_exists($handlerClass)){
                throw new \Exception('Клас не найден');
            }
            $class = new $handlerClass;
            if(!$class instanceof Handler){
                throw new \Exception('обработчик для блока должен быть с интерфейсом Handler');
            }
            return $class;
        }
        return new DefaultHandler();
    }

    private function getConfigByType($type){
        return $this->configs[$type];
    }
}

