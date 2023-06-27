<?php

namespace Gtd\VueEditor\Block;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class Finder
{

    const DEFAULT_PATH = 'default';

    const EXTERNAL_PATH = 'external';


    private $externalPathRewrite = "";

    public function __construct($externalPathRewrite = "")
    {
        $this->externalPathRewrite = $externalPathRewrite;
    }


    /**
     * @return string[]
     */
    private function getBlockPaths(): array
    {
        $paths = [
            self::DEFAULT_PATH => $_SERVER['DOCUMENT_ROOT'] . '/local/modules/gtd.vueeditor/app/src/block',
            self::EXTERNAL_PATH => $_SERVER['DOCUMENT_ROOT'] . '/local/modules/gtd.vueeditor/app/src/ext_block',
        ];
        if ($this->externalPathRewrite != "") {
            $paths[self::EXTERNAL_PATH] = $this->externalPathRewrite;
        }
        return $paths;
    }

    public static function getExtBlockPath(): string
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

        if ($where === self::DEFAULT_PATH || $where === self::EXTERNAL_PATH) {
            $paths[] = $availablePath[$where];
        } else {
            return $availablePath;
        }
        return $paths;
    }

    /**
     * @param $where string all | default | external
     * @return BlockConfig []
     */
    public function find(string $where = 'all'): array
    {
        $arConfigPath = [];
        $arConfig = [];
        $rootPaths = $this->getSearchPath($where);
        foreach ($rootPaths as $rootType => $rootPath) {
            if ($rootType === $this::EXTERNAL_PATH && !is_dir($rootPath)) {
                continue;
            }
            $arConfigPath = array_merge($arConfigPath, $this->iterationFindConfigPath($rootPath));
        }
        if (!empty($arConfigPath)) {
            foreach ($arConfigPath as $configPath) {
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
        foreach ($regex as $file) {
            $configPaths[] = $file[0];
        }
        return $configPaths;
    }

    /**
     * @param $path
     * @return BlockConfig|null
     */
    private function getBlockConfigByFile($path)
    {
        if (is_file($path)) {
            $raw = file_get_contents($path);
            $json = json_decode($raw);
            return new BlockConfig($json);
        }
        return null;
    }
}