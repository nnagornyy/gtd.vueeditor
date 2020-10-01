<?php

use Bitrix\Main\Loader;
use Gtd\VueEditor\Preset\ApiService;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
Loader::includeModule('gtd.vueeditor');

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$api = new ApiService();
switch ($request->getQuery('action')){
    case 'save':
        $api->save();
        break;
    case 'load':
        $api->load();
        break;
    case 'list':
        $api->getList();
        break;
    default:
        break;
}
