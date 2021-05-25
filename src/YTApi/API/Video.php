<?php

namespace App\YTApi\API;

use App\Entity\VideoInfo;
use App\YTApi\BaseApi;

class Video extends BaseApi
{
    public function info(string $videoId): VideoInfo
    {
        // $googleApi = $this->baseUri.'/videos?'
        //     .'id='.$id.'&key='.$this->googleApiKey.'&part=snippet';

        $info = new VideoInfo();

        // $response = $this->client->request(
        //     'GET',
        //     $googleApi
        // );

        $params = ['id' => $videoId];

        $content =  $this->request('GET', 'videos', $params);


        // $statusCode = $response->getStatusCode();

        // $content = $response->toArray();

        if (isset($content['items'][0]['snippet'])) {
            $snippet = $content['items'][0]['snippet'];

            $info->title = $snippet['title'];
            $info->description = $snippet['description'];
        }

        return $info;

    }
}
