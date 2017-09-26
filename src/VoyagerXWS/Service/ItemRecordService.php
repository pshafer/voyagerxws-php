<?php

namespace VoyagerXWS\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

use VoyagerXWS\Exceptions\ServiceException;
use VoyagerXWS\Response\ItemsRecordResponse;

class ItemRecordService
{
    private $client;

    public function __construct (Client $client) {
        $this->client = $client;
    }

    /**
     * Returns a MarcRecordResponse if the record is found
     *
     * @param $bibid BibID of the request you want to retrieve
     *
     * @return \VoyagerXWS\Response\MarcRecordResponse if api request is successful
     *
     * @throws \VoyagerXWS\Exceptions\ServiceException thrown if an error occurs making request from the server
     */
    public function getRecord($bibid)
    {
        $request = new Request('GET', 'record/' . $bibid . '/items?view=full');
        $response = $this->client->send($request);

        if($response->getStatusCode() === 200) {
            $body = $response->getBody()->getContents();

            return new ItemsRecordResponse($body);
        }

        throw new ServiceException($response->getStatusCode(), "An error occurred communicating with API");
    }
}