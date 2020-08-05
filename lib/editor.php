<?php

namespace Gtd\VueEditor;

use Bitrix\Main\Page\Asset;

class Editor {

    const ASSET_SUB_DIR = '/app/asset/';

    private $moduleDir;

    private $assetDir;

    private $inputId;

    private $value = [];

    private $allowBlocks = [];

    /**
     * @var string
     */
    private $input;

    public function __construct($baseDir = "")
    {
        $this->inputId = $this->generateInputId();
        if($baseDir === ""){
            $this->moduleDir = dirname(__DIR__);
            $this->moduleDir = str_replace($_SERVER['DOCUMENT_ROOT'],'', $this->moduleDir);
        }
        $this->assetDir = $this->moduleDir.self::ASSET_SUB_DIR;
    }

    public function initEditor(){
        $this->loadJsFiles();
        $this->renderHtml();;
    }

    private function renderHtml(){
        echo "<div id='$this->inputId'></div>";
        echo "<script>document.gtdEditor('$this->value', '$this->input', '$this->inputId', '$this->allowBlocks')</script>";
    }

    private function loadJsFiles(){
        $asset = Asset::getInstance();
        $asset->addString('<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">');
        foreach ($this->getJsFile() as $jsFileName){
            $asset->addJs($this->assetDir.'/'.$jsFileName);
        }
    }

    /**
     * @return array|false
     */
    private function getJsFile(){
        $assetFiles = scandir($this->getAssetDir());
        return array_filter($assetFiles, function ($arr1){
            return stripos($arr1,'.js');
        });
    }

    /**
     * @return string
     */
    public function getAssetDir($from_root = true)
    {
        if($from_root){
            return $_SERVER['DOCUMENT_ROOT'].$this->assetDir;
        }
        return $this->assetDir;
    }

    private function generateInputId(){
        return "gtd_vue_editor_".rand(0, 999);
    }

    /**
     * @param array $input
     */
    public function setInput($input)
    {
        $this->input = $input;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public static function getFields(){

    }

    /**
     * @param array $allowBlocks
     */
    public function setAllowBlocks(array $allowBlocks): void
    {
        $this->allowBlocks = json_encode($allowBlocks);
    }

    /**
     * @return array
     */
    public function getAllowBlocks(): array
    {
        return $this->allowBlocks;
    }
}