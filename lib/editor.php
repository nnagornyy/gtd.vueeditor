<?php

namespace Gtd\VueEditor;

class Editor {

    const MODULE_DIR = '/local/modules/gtd.vueeditor/';

    public function __construct()
    {
    }

    public static function initEditor(){
        $asset = \Bitrix\Main\Page\Asset::getInstance();
        $asset->addJs(self::MODULE_DIR.'app/asset/script.js');
        $asset->addJs(self::MODULE_DIR.'app/asset/field.script.js');
        echo '<div id="vue_editor"></div>';
    }
}