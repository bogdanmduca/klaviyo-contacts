<?php

namespace App\Services;

class CreateListAction extends ApiService
{
    public function execute($name)
    {
        $queryAttributes['api_key'] = $this->token;
        $query = http_build_query($queryAttributes);

        $url = $this->baseUrl . "/v2/lists";
        $url = $url . "?{$query}";

        $formData = [
            'list_name' => $name
        ];

        return $this->api("POST", $url, $formData);
    }

    private function api($method, $url, $body = null)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request($method, $url, [
            'form_params' => $body,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        return json_decode($response->getBody());
    }
}
