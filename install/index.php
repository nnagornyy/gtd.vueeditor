<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Gtd\VueEditor\IBlockField;

class gtd_vueeditor extends CModule {

    public $MODULE_ID = 'gtd.vueeditor';

    public $MODULE_NAME = 'Блочный редактор контента';

    public $MODULE_DESCRIPTION = 'Блочный редактор нового поколения';

    public $PARTNER_NAME = 'GTD';

    public $PARTNER_URI = 'http://betaagency.ru';

    public function __construct(){

    }
    public function DoInstall()
    {
        $this->InstallEvents();
        RegisterModule($this->MODULE_ID);
        $this->DoInstallDb();
        $this->createExtBlockDir();
    }

    public function InstallEvents()
    {
        $em = EventManager::getInstance();
        Loader::includeModule('iblock');
        $em->registerEventHandlerCompatible(
            "iblock",
            "OnIBlockPropertyBuildList",
            $this->MODULE_ID,
            IBlockField::class,
            "GetUserTypeDescription"
        );
    }

    public function DoUninstall()
    {
        $this->DoUninstallDb();
        UnRegisterModule($this->MODULE_ID);
    }

    private function DoInstallDb(){
        Loader::includeModule($this->MODULE_ID);
        Gtd\VueEditor\Preset\PresetTable::getEntity()->createDbTable();
    }

    private function DoUninstallDb(){
        Loader::includeModule($this->MODULE_ID);
        $conn = \Bitrix\Main\Application::getConnection();
        if($conn->isTableExists(\Gtd\VueEditor\Preset\PresetTable::getTableName())){
            $conn->dropTable(\Gtd\VueEditor\Preset\PresetTable::getTableName());
        }
    }

    private function createExtBlockDir(){
        Loader::includeModule($this->MODULE_ID);
        $extBlockPath = \Gtd\VueEditor\Block\Finder::getExtBlockPath();
        if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/local/vueeditor')) {
            mkdir($_SERVER['DOCUMENT_ROOT'].'/local/vueeditor', 0777, true);
        }
        if (!file_exists($extBlockPath)) {
            mkdir($extBlockPath, 0777, true);
        }
    }

}