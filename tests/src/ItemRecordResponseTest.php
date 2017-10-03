<?php

use VoyagerXWS\Response\ItemsRecordResponse;
use VoyagerXWS\Fields\ItemField;
use VoyagerXWS\Fields\ItemDataField;
use PHPUnit\Framework\TestCase;

class ItemRecordResponseTest extends TestCase
{
    /**
     * @var MarcRecordResponse
     */
    private $testResponse;

    public function setUp()
    {
        parent::setUp();
        $xmlDoc = file_get_contents(__DIR__ . '/../data/items/record1.xml');
        $this->testResponse = new ItemsRecordResponse($xmlDoc);
    }

    /**
     *
     * @covers ItemsRecordResponse::__construct()
     */
    public function testItemRecordResponseConstructor()
    {

        $this->assertInstanceOf(ItemsRecordResponse::class, $this->testResponse);
    }

    /**
     * Tests a successful status code
     *
     * @covers ItemsRecordResponse::getStatus()
     */
    public function testItemRecordResponseStatusSuccess()
    {
        $status = $this->testResponse->getStatus();

        $this->assertNotEmpty($status);
        $this->assertArrayHasKey('status-code', $status);
        $this->assertEquals(0, $status['status-code']);
    }

    /**
     * Tests a response where records don't exist or the retrieval failed
     *
     * @covers ItemsRecordResponse::getStatus()
     */
    public function testItemRecordResponseStatusFail()
    {
        $xmlDoc = file_get_contents(__DIR__ . '/../data/items/itemsnotfound.xml');
        $response = new ItemsRecordResponse($xmlDoc);

        $status = $response->getStatus();

        $this->assertNotEmpty($status);
        $this->assertArrayHasKey('status-code', $status);
        $this->assertNotEquals(0, $status['status-code']);
    }

    /**
     * Test getting all items in a ItemRecordResponse
     *
     * @covers ItemsRecordResponse::getItems()
     */
    public function testItemRecordResponseGetItems()
    {
        $items = $this->testResponse->getItems();

        $this->assertEquals(1,count($items));

        foreach($items as $item){
            $this->assertInstanceOf(ItemField::class, $item);
        }
    }
    
    /**
     * Test Retrieving and ItemDateField from a ItemField
     */
    public function testItemDataField()
    {
        $items = $this->testResponse->getItems();
        
        $this->assertEquals(1,count($items));
        $ItemField = $items[0];
        
        $this->assertInstanceOf(ItemField::class, $ItemField);
        
        $ItemDataField = $ItemField->getItemData('location');
        
        $this->assertInstanceOf(ItemDataField::class, $ItemDataField[0]);
        $this->assertEquals('Main', $ItemDataField[0]->attr('code'));
        $this->assertEquals('2', $ItemDataField[0]->attr('id'));

    }

}