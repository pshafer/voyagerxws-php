<?php

namespace VoyagerXWS\Service;

use VoyagerXWS\Client\VoyagerXWSClient;
use VoyagerXWS\Exceptions\ServiceException;
use VoyagerXWS\Response\MarcRecordResponse;


class MarcRecordService
{
    private $client;

    /**
     * MarcRecordService constructor.
     * @param \VoyagerXWS\Client\VoyagerXWSClient $client
     */
    public function __construct (VoyagerXWSClient $client) {
        $this->client = $client;
    }

    /**
     * Returns a MarcRecordResponse for the provided bibid
     *
     * @param $bibid
     *
     * @return \VoyagerXWS\Response\MarcRecordResponse
     *
     * @throws \VoyagerXWS\Exceptions\ServiceException
     */
    public function getRecord($bibid)
    {
        $response = $this->client->getMarcRecord(['bibid' => $bibid ]);
        if($response['statusCode'] === 200) {
            $body = $response['body']->getContents();
            return new MarcRecordResponse($body);
        }

        throw new ServiceException($response['statusResponse'], $response['statusCode']);

    }
}