<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//delete file !
global $USER;
$error = true;
if($USER->IsAdmin() && $_REQUEST['id'] > 0){
    $fileId = $_REQUEST['id'];
    $file = CFile::GetByID($fileId)->Fetch();
    if($file && $file['MODULE_ID'] === 'gtd.vueeditor'){
        CFile::Delete($fileId);
        $error = false;
    }
}
$data = [
    'error' => $error,
];
header('Content-Type: application/json');
echo json_encode($data);