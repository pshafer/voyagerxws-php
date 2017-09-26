<?php
require_once('vendor/autoload.php');

use VoyagerXWS\ClientFactory;
use VoyagerXWS\Service\MarcRecordService;
use VoyagerXWS\Service\ItemRecordService;


if(isset($_GET['bibid']) && is_numeric($_GET['bibid'])){

    $bibid = $_GET['bibid'];
    try{
        $client = ClientFactory::create([
            'base_uri' => 'http://library.rowan.edu:7014/vxws/'
        ]);
    }catch(Exception $e) {
        http_response_code(400);
        die();

    }

}else{
    http_response_code(400);
    die();
}


$responseBody = new stdClass();

try{
    $marcService = new MarcRecordService($client);
    $marcRecord = $marcService->getRecord($bibid);
    $marcStatus = $marcRecord->getStatus();

    if($marcStatus['status-code'] === 0) {
        $bibidField = $marcRecord->getControlField("001");
        $responseBody->bibID = $bibidField->getValue();

        $titleField = $marcRecord->getDataField('245',"1", "0");
        if(count($titleField) >= 1) {
            $responseBody->title = $titleField[0]->getSubfield('a');

        }

        $isbnField = $marcRecord->getDataField("020");
        if(count($isbnField) >= 1) {
            $responseBody->isbn = $isbnField[0]->getSubfield('a');
        }

        try{
            $itemService = new ItemRecordService($client);
            $itemsResponse = $itemService->getRecord($bibid);
        }catch(Exception $e){
            die($e);
        }

        $responseItems = array();
        $itemStatus = $itemsResponse->getStatus();

        if($itemStatus['status-code'] === 0) {
            $items = $itemsResponse->getItems();

            foreach($items as $item) {

                $itemRecord = new stdClass();

                $location = $item->getItemData('location');
                if(count($location) >=1 ){
                    $itemRecord->locationCode = $location[0]->attr('code');
                    $itemRecord->location  = $location[0]->text();
                }

                $callNumber = $item->getItemData('callnumber');
                if(count($callNumber) >= 1){
                    $itemRecord->callNumber = $callNumber[0]->text();
                }

                $typeDesc = $item->getItemData('typeDesc');
                if(count($typeDesc) >= 1){
                    $itemRecord->type = $typeDesc[0]->text();
                }

                $typeCode = $item->getItemData('typeCode');
                if(count($typeCode) >= 1) {
                    $itemRecord->typeCode = intval($typeCode[0]->text());
                }

                $status = $item->getItemData('itemStatus');
                if(count($status) >= 1){
                    $itemRecord->status = $status[0]->text();
                }

                $statusCode = $item->getItemData('itemStatusCode');
                if(count($statusCode) >= 1) {
                    $itemRecord->statusCode = intval($statusCode[0]->text());
                }

                $responseItems[] = $itemRecord;
            }
        }

        if(count($responseItems) > 0){
            $responseBody->items = $responseItems;
        }

    }else{
        $responseBody->status = ['status-code' => $marcStatus['status-code'], 'status-text' => $marcStatus['status-text']];
    }

}catch(Exception $e) {
    $responseBody->status = ['status-code' => -1, 'status-text' => 'Error Connecting to VXWS Service'];
}

http_response_code(200);
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header('Content-type: application/json');
print json_encode($responseBody);