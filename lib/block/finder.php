<?php
namespace Gtd\VueEditor\Block;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class Finder {

    const DEFAULT_PATH = 'default';

    const EXTERNAL_PATH = 'external';


    /**
     * @return string[]
     */
    private function getBlockPaths(): array
    {
        return [
            'default' => $_SERVER['DOCUMENT_ROOT'].'/local/modules/gtd.vueeditor/app/src/block',
            'external' => $_SERVER['DOCUMENT_ROOT'].'/local/modules/gtd.vueeditor/app/src/ext_block',
        ];
    }

    public static function getExtBlockPath():string
    {
        $ob = new self();
        $paths = $ob->getBlockPaths();
        return $paths['external'];
    }

    /**
     * @param string $where
     * @return array|string[]
     */
    private function getSearchPath($where = 'all'): array
    {
        $paths = [];
        $availablePath = $this->getBlockPaths();

        if($where === self::DEFAULT_PATH || $where === self::EXTERNAL_PATH){
            $paths[] = $availablePath[$where];
        }else{
            return $availablePath;
        }
        return $paths;
    }

    /**
     * @param $where string all | default | external
     * @return \Gtd\VueEditor\Block\BlockConfig []
     */
    public function find(string $where = 'all'): array
    {
        $arConfigPath = [];
        $arConfig = [];
        $rootPaths = $this->getSearchPath($where);
        foreach ($rootPaths as $rootPath){
            $arConfigPath = array_merge($arConfigPath, $this->iterationFindConfigPath($rootPath));
        }
        if(!empty($arConfigPath)){
            foreach ($arConfigPath as $configPath){
                $arConfig[] = $this->getBlockConfigByFile($configPath);
            }
        }
        return $arConfig;
    }

    /**
     * @param $path
     * @return string[]
     */
    private function iterationFindConfigPath($path): array
    {
        $configPaths = [];
        $directory = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($directory);
        $regex = new RegexIterator($iterator, '/^.+\.conf.json$/i', RecursiveRegexIterator::GET_MATCH);
        foreach($regex  as $file){
            $configPaths[] = $file[0];
        }
        return $configPaths;
    }

    /**
     * @param $path
     * @return \Gtd\VueEditor\Block\BlockConfig|null
     */
    private function getBlockConfigByFile($path){
        if(is_file($path)){
            $raw = file_get_contents($path);
            $json = json_decode($raw);
            return new \Gtd\VueEditor\Block\BlockConfig($json);
        }
        return  null;
    }
}