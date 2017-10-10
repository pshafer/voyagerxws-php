<?php


use VoyagerXWS\Response\MarcRecordResponse;
use VoyagerXWS\Fields\DataField;
use VoyagerXWS\Fields\ControlField;

use PHPUnit\Framework\TestCase;

class MarcRecordResponseTest extends TestCase
{

    /**
     * MarcRecordResponse
     */
    private $response;

    public function setUp()
    {
        parent::setUp();
        $xmlDoc = file_get_contents(__DIR__ . '/../data/marc/record1.xml');
        $this->response = new MarcRecordResponse($xmlDoc);
    }

    /**
     * Test to make sure a record was found using the status-code
     *
     * @covers MarcRecordResponse::getStatus()
     */
    public function testRecordExists()
    {

        $status = $this->response->getStatus();

        $this->assertNotEmpty($status);
        $this->assertArrayHasKey('status-code', $status);
        $this->assertEquals(0, $status['status-code']);
    }

    /**
     * Test to make the the response reports that no record was found
     *
     * @covers MarcRecordResponse::getStatus()
     */
    public function testRecordDoesNotExist()
    {
        $xmlDoc = file_get_contents(__DIR__ . '/../data/marc/notfound.xml');

        $response = new MarcRecordResponse($xmlDoc);
        $status = $response->getStatus();

        $this->assertNotEmpty($status);
        $this->assertArrayHasKey('status-code', $status);
        $this->assertNotEquals(0, $status['status-code']);
    }

    /**
     * Test to make sure the you can retrieve the leader field
     * 
     * @covers MarcRecordResponse::getLeader()
     */
    public function testGetLeader()
    {
        $leader = $this->response->getLeader();

        $this->assertInstanceOf(\VoyagerXWS\Fields\LeaderField::class, $leader);
        $this->assertEquals('000', $leader->getTag());
    }

    /**
     * Test getting a sigle control field by tag name
     *
     * @covers MarcRecordResponse::getControlField()
     */
    public function testGetControlField()
    {

        $fields = $this->response->getControlField('001');

        $this->assertInternalType('array', $fields);
        $this->assertEquals(1, count($fields));
        
        $controlField = $fields[0];

        $this->assertInstanceOf(ControlField::class, $controlField);
        $this->assertEquals('001', $controlField->getTag());
    }

    /**
     * Test getting all the control fields in the document
     *
     * @covers MarcRecordResponse::getControlFields()
     */
    public function testGetControlFields()
    {

        $controlFields = $this->response->getControlFields();

        $this->assertInternalType('array', $controlFields);
        $this->assertEquals(3,count($controlFields));
        foreach($controlFields as $controlField){
            $this->assertInstanceOf(ControlField::class, $controlField);
        }
    }

    /**
     * Test a single DataField using a a tag name
     *
     * @covers MarcRecordResponse::getDataField()
     */
    public function testGetSingleDataField()
    {
        $dataField = $this->response->getDataField('245');
        $this->assertEquals(1,count($dataField));
        $this->assertInstanceOf(DataField::class, $dataField[0]);
        $this->assertEquals('245', $dataField[0]->getTag());
    }

    /**
     * Test retrieving multiple DataFields using a tag name
     *
     * @covers MarcRecordResponse::getDataField()
     */
    public function testGetMultipleDataFields()
    {
        $dataFields = $this->response->getDataField('035');
        $this->assertEquals(2,count($dataFields));

        foreach($dataFields as $dataField) {
            $this->assertInstanceOf(DataField::class, $dataField);
            $this->assertEquals('035', $dataField->getTag());
        }
    }

    /**
     * Test retrieve all DataFields in the record
     *
     * @covers MarcRecordResponse::getDataFields()
     */
    public function testGetAllDataFields()
    {
        $dataFields = $this->response->getDataFields();

        $this->assertEquals(30,count($dataFields));
        foreach($dataFields as $dataField) {
            $this->assertInstanceOf(DataField::class, $dataField);
        }
    }
}