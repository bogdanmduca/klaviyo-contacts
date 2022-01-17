<?php

namespace App\Services;

class PersonService extends ApiService
{
    public function update($remoteId, $attributes)
    {
        $url = $this->baseUrl . "/v1/person/{$remoteId}";
        $queryAttributes = $attributes;
        $queryAttributes['api_key'] = $this->token;
        $query = http_build_query($queryAttributes);

        $url = $url . "?{$query}";
        return $this->api("PUT", $url);
    }

    private function api($method, $url, $body = null)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request($method, $url, [
            'body' => json_encode($body),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody());
    }
}
