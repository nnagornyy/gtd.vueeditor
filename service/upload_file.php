<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
//save file
if(!empty($_FILES['file'])){
    $arFile = $_FILES['file'];
    $arFile['MODULE_ID'] = 'gtd.vueeditor';
    $fileId = CFile::SaveFile($arFile,'vueeditor');
    if($fileId > 0){
        $arDbFile = CFile::GetFileArray($fileId);
        $data = [
            'id' => (int)$arDbFile['ID'],
            'type' => $arDbFile['CONTENT_TYPE'],
            'originalName' => $arDbFile['ORIGINAL_NAME'],
            'fileSize' => (int)$arDbFile['FILE_SIZE'],
            'src' => $arDbFile['SRC']
        ];
    }else{
        http_response_code(404);
    }
}
header('Content-Type: application/json');
echo json_encode($data);