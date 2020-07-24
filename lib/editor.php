<?php

namespace Gtd\VueEditor;

use Bitrix\Main\Page\Asset;

class Editor {

    const ASSET_SUB_DIR = '/app/asset/';

    private $moduleDir;

    private $assetDir;

    public function __construct($baseDir = "")
    {
        if($baseDir === ""){
            $this->moduleDir = dirname(__DIR__);
            $this->moduleDir = str_replace($_SERVER['DOCUMENT_ROOT'],'', $this->moduleDir);
        }
        $this->assetDir = $this->moduleDir.self::ASSET_SUB_DIR;
    }

    public function initEditor(){
        $this->loadJsFiles();
        echo '<div id="vue_editor"></div>';
    }

    private function loadJsFiles(){
        $asset = Asset::getInstance();
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
}