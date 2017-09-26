<?php

namespace VoyagerXWS;

use GuzzleHttp\Client;


class ClientFactory
{
    const USER_AGENT = 'VoyagerXWS Client';

    /**
     * Creates a GuzzleHTTP\Client
     *
     * @param $config
     * @return \GuzzleHttp\Client
     */
    public static function create($config)
    {
        $defaultConfig = [
          'headers' => [
              'User-Agent' => self::USER_AGENT
          ]
        ];

        if(isset($config['base_uri'])){
            $client = new Client(array_merge($defaultConfig, $config));
            return $client;
        }else{
            throw new ClientException('No base_uri provided, cannot create client', '001');
        }
    }
}

?>