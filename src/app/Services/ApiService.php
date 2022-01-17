<?php

namespace App\Services;

class ApiService
{
    protected $baseUrl;

    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('api.url');
        $this->token = config('api.token');
    }
}
