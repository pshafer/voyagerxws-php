<?php

namespace VoyagerXWS\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use VoyagerXWS\Exceptions\ClientException;

class VoyagerXWSClient extends GuzzleClient {

    const USER_AGENT = 'VoyagerXWS Client';


    public static function create($config = []) {

        if(isset($config['baseUrl'])){
            $service_description = new Description(
                ['baseUrl' => $config['baseUrl']] + (array) json_decode(file_get_contents(__DIR__ . '/../../../config/services.json'), TRUE)

            );

            $client = new Client([
                'headers' => [
                    'User-Agent' => self::USER_AGENT
                ]
            ]);

            return new static($client, $service_description, NULL, NULL, NULL, $config);
        }else{
            throw new ClientException('Invalid Configuration - Cannot create client', 000);
        }
    }
}