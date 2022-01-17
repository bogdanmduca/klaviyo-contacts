<?php

namespace App\Services;

class ListService extends ApiService
{
    public function addProfile($listId, $attributes)
    {
        $queryAttributes['api_key'] = $this->token;
        $query = http_build_query($queryAttributes);

        $url = $this->baseUrl . "/v2/list/{$listId}/members";
        $url = $url . "?{$query}";

        $body['profiles'] = $attributes;

        return $this->api("POST", $url, $body);
    }

    public function removeProfile($listId, $email)
    {
        $queryAttributes['api_key'] = $this->token;
        $query = http_build_query($queryAttributes);

        $url = $this->baseUrl . "/v2/list/{$listId}/members";
        $url = $url . "?{$query}";

        $body['emails'] = [$email];

        return $this->api("DELETE", $url, $body);
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
