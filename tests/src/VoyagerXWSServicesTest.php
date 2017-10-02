<?php

use VoyagerXWS\Client\VoyagerXWSClient;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;


class VoyagerXWSServicesTest extends TestCase
{
    /**
     * @var \GuzzleHttp\Client;
     */
    public $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = VoyagerXWSClient::create(array('baseUrl' => $_SERVER['API_BASE']));
    }

    /**
     * Test that MarcRecordService can be created and that it can return a MarcRecordResponse object
     */
    public function testMarcRecordService()
    {

        $service = new \VoyagerXWS\Service\MarcRecordService($this->client);
        $this->assertInstanceOf(\VoyagerXWS\Service\MarcRecordService::class, $service);

        $response = $service->getRecord($_GET['test_bibid']);
        $this->assertInstanceOf(\VoyagerXWS\Response\MarcRecordResponse::class, $response);
    }

    /**
     * Test that ItemRecordService can be created and that it can return a ItemsRecordResponse object
     */
    public function testItemRecordService()
    {
        $service = new \VoyagerXWS\Service\ItemRecordService($this->client);

        $this->assertInstanceOf(\VoyagerXWS\Service\ItemRecordService::class, $service);

        $response = $service->getRecord($_GET['test_bibid']);
        $this->assertInstanceOf(\VoyagerXWS\Response\ItemsRecordResponse::class, $response);
    }

}