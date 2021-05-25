<?php

namespace App\Service;

use App\Entity\VideoInfo;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class YTHandler
{
    private string $baseUri = 'https://www.googleapis.com/youtube/v3';

    public function __construct(
        private string $googleApiKey,
        private HttpClientInterface $client
    ) {
    }

    public function extractYTId(string $url): string
    {
        preg_match(
            "/^(?:http(?:s)?:\/\/)?"
            ."(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|"
            ."(?:embed|v|vi|user)\/))([^\?&\"'>]+)/",
            $url,
            $matches
        );

        return $matches[1] ?? '';
    }

    public function fetchInfo(string $id): VideoInfo
    {
        $googleApi = $this->baseUri.'/videos?'
            .'id='.$id.'&key='.$this->googleApiKey.'&part=snippet';

        $info = new VideoInfo();

        $response = $this->client->request(
            'GET',
            $googleApi
        );

        // $statusCode = $response->getStatusCode();

        $content = $response->toArray();

        if (isset($content['items'][0]['snippet'])) {
            $snippet = $content['items'][0]['snippet'];

            $info->title = $snippet['title'];
            $info->description = $snippet['description'];
        }

        return $info;
    }

    public function search(string $query, string $pageToken = null): array
    {
        // $url = $this->baseUri.'/search?'
        // .'q='.$query
        //     .'&key='.$this->googleApiKey;

        $params = [
            'q' => $query,
            'key' => $this->googleApiKey,
            'type' => 'video',
            'videoEmbeddable' => 'true',
            'videoLicense' => 'youtube',
        ];

        if ($pageToken) {
            $params['pageToken'] = $pageToken;
        }

        $url = $this->baseUri.'/search?'.http_build_query($params);

        // maxResults
        // pageToken
        //type=video
        //videoEmbeddable=true

        $response = $this->client->request(
            'GET',
            $url
        );

        // $statusCode = $response->getStatusCode();

        $content = $response->toArray();

        return $content;

    }
}
