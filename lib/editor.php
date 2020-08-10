<?php

namespace Gtd\VueEditor;

use Bitrix\Main\Page\Asset;

class Editor {

    const ASSET_SUB_DIR = '/app/asset/';

    private $moduleDir;

    private $assetDir;

    private $app_id;

    private $property_id;

    private $input_name;

    private $value = '[]';

    private $allowBlocks = [];

    /**
     * @var string
     */
    private $input;

    public function __construct($baseDir = "")
    {
        $this->app_id = $this->generateAppId();
        if($baseDir === ""){
            $this->moduleDir = dirname(__DIR__);
            $this->moduleDir = str_replace($_SERVER['DOCUMENT_ROOT'],'', $this->moduleDir);
        }
        $this->assetDir = $this->moduleDir.self::ASSET_SUB_DIR;
    }

    public function initEditor(){
        $this->loadJsFiles();
        $this->renderHtml();
    }

    private function renderHtml(){
        echo "<div id='$this->app_id'></div>";
        $this->renderTemplate('editor_script');
    }

    private function renderTemplate($name){
        $path = __DIR__.'/templates/'.$name.'.php';
        if(is_file($path)){
            include $path;
        }
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

    private function generateAppId(){
        return "gtd_vue_editor_".rand(0, 999);
    }


    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return json_encode($this->value);
    }

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->app_id;
    }

    /**
     * @param array $allowBlocks
     */
    public function setAllowBlocks(array $allowBlocks): Editor
    {
        $this->allowBlocks = $allowBlocks;
        return $this;
    }

    /**
     * @return string
     */
    public function getAllowBlocks(): string
    {
        return json_encode($this->allowBlocks);
    }

    /**
     * @param mixed $input_name
     */
    public function setInputName($input_name): Editor
    {
        $this->input_name = $input_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInputName()
    {
        return $this->input_name;
    }

    /**
     * @param mixed $property_id
     */
    public function setPropertyId($property_id)
    {
        $this->property_id = $property_id;
    }

    /**
     * @return mixed
     */
    public function getPropertyId()
    {
        return $this->property_id;
    }
}