<?php

namespace Employment\Model;

use Laminas\Http\{
    Client,
    Request
};
use \RuntimeException;
use \DomainException;

class APIClient
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getEmploymentStatus(int $inn): bool
    {
        $status = false;
        $this->client->setRawBody(json_encode([
            'inn' => $inn,
            'requestDate' => date('Y-m-d')
        ]));

        $response = $this->client->send($request);
        $response = json_decode($response->getBody(), true);

        if(is_array($response) && isset($response['status'])) {
            $status = (bool) $response['status'];
        } else if(is_array($response) && isset($response['code']) && $response['code'] == 'taxpayer.status.service.limited.error') {
            throw new DomainException($response['message'] . ', ' . $response['code']);
        } else {
            throw new RuntimeException('Unable to handle request');
        }

        return $status;
    }
}