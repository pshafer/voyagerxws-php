<?php

namespace VoyagerXWS\Service;

use VoyagerXWS\Client\VoyagerXWSClient;
use VoyagerXWS\Exceptions\ServiceException;
use VoyagerXWS\Response\ItemsRecordResponse;

class ItemRecordService
{
    private $client;

    /**
     * ItemRecordService constructor.
     * @param \VoyagerXWS\Client\VoyagerXWSClient $client
     */
    public function __construct (VoyagerXWSClient $client) {
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
        $response = $this->client->getItemRecords(['bibid' => $bibid ]);
        if($response['statusCode'] === 200) {
            $body = $response['body']->getContents();

            return new ItemsRecordResponse($body);
        }

        throw new ServiceException($response['statusResponse'], $response['statusCode']);
    }
}