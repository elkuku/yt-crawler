<?php

namespace App\YTApi;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class BaseApi
{
    private string $baseUri = 'https://www.googleapis.com/youtube/v3';

    public function __construct(
        private string $googleApiKey,
        private HttpClientInterface $client
    ) {
    }

    protected function request(
        string $method,
        string $api,
        array $params = []
    ): array {
        $params['key'] = $this->googleApiKey;

        $url = $this->baseUri.'/'.$api.'?'.http_build_query($params);

        $response = $this->client->request(
            $method,
            $url
        );

        // $statusCode = $response->getStatusCode();

        $content = $response->toArray();

        return $content;
    }
}
