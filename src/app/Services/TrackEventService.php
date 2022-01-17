<?php

namespace App\Services;

class TrackEventService extends ApiService
{
    public function store($event, $customerProperties)
    {
        $url = $this->baseUrl . "/track";
        $body['token'] = $this->token;
        $body['event'] = $event;
        $body['customer_properties'] = $customerProperties;
        $body['properties'] = [
            'time' => now()->toString()
        ];

        $formData = [
            'data' => json_encode($body)
        ];

        return $this->api("POST", $url, $formData);
    }

    private function api($method, $url, $body = null)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request($method, $url, [
            'form_params' => $body,
            'headers' => [
                'Accept' => 'text/html',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        return json_decode($response->getBody());
    }
}
