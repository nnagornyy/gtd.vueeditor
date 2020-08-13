<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//delete file !

$data = [
    'id' => '99',
    'src' => '/upload/test.jpg'
];

header('Content-Type: application/json');
echo json_encode($data);