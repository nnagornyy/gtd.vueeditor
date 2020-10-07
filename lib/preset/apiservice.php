<?php

namespace Gtd\VueEditor\Preset;

use Bitrix\Main\Application;
use Bitrix\Main\Type\DateTime;

class ApiService{

    private $request;

    private $payload;

    private $result;

    private $errorMessage;

    private $error = false;

    private $code = 200;

    public function __construct()
    {
        $this->request = Application::getInstance()->getContext()->getRequest();
        if($this->request->isPost()){
            $inputJSON = file_get_contents('php://input');
            $this->payload = json_decode($inputJSON, TRUE);
        }
    }

    public function save(){
        global $USER;
        if($this->isPayloadValid()){
            $preset = PresetTable::createObject();
            $preset
                ->setTitle($this->payload['title'])
                ->setCreated(DateTime::createFromTimestamp(time()))
                ->setData(json_encode($this->payload['data']))
                ->setShare(true)
                ->setUserId($USER->GetID())
                ->setBlock($this->payload['block'])
                ->save();
        }else{
            // error
            $this->setError(400, 'переданы не все данные');
        }
        $this->showResponse();
    }

    public function load(){}

    public function getList(){
        $result = [];
        $block = $this->request->getQuery('block');
        $presets = PresetTable::getList([
            'filter' => [
                '=block' => $block
            ],
            'select' => ['*','USER']
        ])->fetchCollection();
        foreach ($presets as $i => $preset){
            $user = $preset->getUser();
            $result[] = [
                'id' => $preset->getId(),
                'title' => $preset->getTitle(),
                'data' => json_decode($preset->getData()),
                'block' => $preset->getBlock(),
                'created' => $preset->getCreated()->format('d.m.Y H:i'),
                'user' => $user->getLastName().' '.$user->getName()
            ];
        }
        $this->result = $result;
        $this->showResponse();
    }

    private function isPayloadValid(){
        $req = ['title','data','block'];
        foreach ($this->payload as $k => $v){
            if(!in_array($k, $req) || empty($v))
                return  false;
        }
        return true;
    }

    private function setError($code, $message){
        $this->error = true;
        $this->errorMessage = $message;
    }

    private function showResponse(){
        header('Content-Type: application/json');
        $response = ['code' => $this->code, 'result' => []];
        if($this->error){
            $response['result']['error'] =  $this->errorMessage;
        }else{
            $response['result'] = $this->result;
        }
        echo json_encode($response);
    }

}