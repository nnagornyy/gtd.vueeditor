<?php
// include js

use Bitrix\Main\Loader;

Loader::includeModule('iblock');

$editor = new Gtd\VueEditor\Editor();
$editor->loadJsFiles();