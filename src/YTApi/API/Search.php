<?php

namespace App\YTApi\API;

use App\YTApi\BaseApi;

class Search extends BaseApi
{
    /**
     * @param string      $query           The q parameter specifies the query term to search for.
     *                                     Your request can also use the Boolean NOT (-) and OR (|) operators
     *                                     to exclude videos or to find videos that are associated with one of
     *                                     several search terms. For example, to search for videos matching either
     *                                     "boating" or "sailing", set the q parameter value to boating|sailing.
     *                                     Similarly, to search for videos matching either "boating" or "sailing"
     *                                     but not "fishing", set the q parameter value to boating|sailing -fishing.
     *                                     Note that the pipe character must be URL-escaped when it is sent in your
     *                                     API request. The URL-escaped value for the pipe character is %7C.
     * @param int         $maxResults      The maxResults parameter specifies the maximum number of items that should
     *                                     be returned in the result set. Acceptable values are 0 to 50, inclusive.
     *                                     The default value is 5.
     * @param string|null $pageToken       The pageToken parameter identifies a specific page in the result set that
     *                                     should be returned. In an API response, the nextPageToken and prevPageToken
     *                                     properties identify other pages that could be retrieved.
     * @param string      $type            The type parameter restricts a search query to only retrieve a particular
     *                                     type of resource. The value is a comma-separated list of resource types.
     *                                     The default value is video,channel,playlist.
     *
     *                                      Acceptable values are:
     *                                      * channel
     *                                      * playlist
     *                                      * video
     * @param string      $videoEmbeddable The videoEmbeddable parameter lets you to restrict a search to only
     *                                     videos that can be embedded into a webpage. If you specify a value for
     *                                     this parameter, you must also set the type parameter's value to video.
     *                                     Acceptable values are:
     *                                     * any – Return all videos, embeddable or not.
     *                                     * true – Only retrieve embeddable videos.
     *
     * @return array
     */
    public function list(
        string $query,
        int $maxResults = null,
        string $pageToken = null,
        string $type = '',
        string $videoEmbeddable = 'any',
    ): array {
        $params = [
            'q' => $query,
        ];

        if ($maxResults) {
            $params['maxResults'] = $maxResults;
        }

        if ($videoEmbeddable) {
            $params['videoEmbeddable'] = $videoEmbeddable;
        }

        if ($type) {
            $params['type'] = $type;
        }

        if ($pageToken) {
            $params['pageToken'] = $pageToken;
        }

        return $this->request('GET', 'search', $params);
    }
}
