<?php

namespace VoyagerXWS\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

use VoyagerXWS\Exceptions\ServiceException;
use VoyagerXWS\Response\MarcRecordResponse;


class MarcRecordService
{
    private $client;

    public function __construct (Client $client) {
        $this->client = $client;
    }

    public function getRecord($bibid)
    {
        $request = new Request('GET','record/' . $bibid . '/?view=full');
        $response = $this->client->send($request);

        if($response->getStatusCode() === 200) {
            $body = $response->getBody()->getContents();

            return new MarcRecordResponse($body);
        }

        throw new ServiceException($response->getStatusCode(), "An error occurred communicating with API");

    }
}