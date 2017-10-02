<?php


use VoyagerXWS\Client\VoyagerXWSClient;
use VoyagerXWS\Exceptions\ClientException;
use PHPUnit\Framework\TestCase;


class VoyagerXWSClientTest extends TestCase
{

    public function setUp() {
        parent::setUp();

    }

    /**
     * Tests to make sure a client can be created
     * @covers VoyagerXWSClient::create
     */
    public function testClientCreated()
    {
        $config = array(
            'baseUrl' => $_SERVER['API_BASE']
        );

        try{
            $client = VoyagerXWSClient::create($config);

        }catch(Exception $e) {

        }

        $this->assertInstanceOf(VoyagerXWSClient::class, $client);

    }

    /**
     * Tests to make sure a ClientException is thrown if the client cannot be created
     *
     * @covers VoyagerXWSClient::create
     */
    public function testClientCreateError()
    {
        $config = array();
        $this->expectException(ClientException::class);
        $client = VoyagerXWSClient::create($config);

    }

}