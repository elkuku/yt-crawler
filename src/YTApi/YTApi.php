<?php

namespace App\YTApi;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @property-read  API\Search $search  Search API
 * @property-read  API\Video  $video   Video API
 */
class YTApi
{
    public function __construct(
        private string $googleApiKey,
        private HttpClientInterface $client
    ) {
    }

    public function __get($name)
    {
        $class = 'App\\YTApi\\API\\'.ucfirst($name);

        if (class_exists($class)) {
            if (isset($this->$name) === false) {
                $this->$name = new $class($this->googleApiKey, $this->client);
            }

            return $this->$name;
        }

        throw new \InvalidArgumentException(
            sprintf(
                'Argument %s produced an invalid class name: %s',
                $name,
                $class
            )
        );
    }
}
