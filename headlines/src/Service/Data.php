<?php

namespace App\Service;

use \App\Service\Advert;
use \GuzzleHttp\Client;

class Data extends Base
{

    private $client;

    /**
     * return a single news item by slug and type
     *
     * @param string $slug
     * @param [type] $type
     *
     * @return void
     */
    public function getSingle(string $slug, $type)
    {

        $cacheName = 'data.get_single_' . urlencode($slug) . '_' .$type;
        $res       = $this->getCache()->get($cacheName);

        if (!$res) {

        $response = $this->getClient()->request('GET', 'item/' . $slug);

        if ($response->getStatusCode() === 200) {

            $body          = $response->getBody();
            $res           = json_decode($body, true);
            $res['advert'] = $this->getAdvert($res['tag']);
            
            $this->getCache()->set($cacheName, $res);

        }

        }

        $this->setResult($res);

    }

    /**
     * get multiple tags from the api
     *
     * @return void
     */
    public function getMultipleTag()
    {

        $cacheName = 'data.get_multiple_tag';
        $res       = $this->getCache()->get($cacheName);

        if (!$res) {

            $response = $this->getClient()->request('GET', 'tag/all');

            if ($response->getStatusCode() === 200) {

                $body = $response->getBody();
                $res  = json_decode($body, true);

                $this->getCache()->set($cacheName, $res);

            }

        }

        $this->setResult($res);

    }

    /**
     * get an advert text from the db
     *
     * @param string $tag
     *
     * @return void
     */
    public function getAdvert(string $tag)
    {

        $cacheName = 'data.get_advert_' .$tag;
        $res       = $this->getCache()->get($cacheName);

        if (!$res) {

        $advert = new Advert($this->getEm());
        $advert->getSingleByTitle($tag);
        $advert = $advert->getResult();
        $res    = null;

        if (!empty($advert['data'])) {
            $res = $advert['data'];
            $this->getCache()->set($cacheName, $res);
        }

        }

        return $res;

    }

    /**
     * get multiple news items from the api based on tag query
     *
     * @param string $query
     * @param integer $limit
     *
     * @return void
     */
    public function getMultiple(string $query = '', $limit = 10)
    {

        $cacheName = 'data.get_multiple_' . urlencode($query) . $limit;
        $res       = $this->getCache()->get($cacheName);

        if (!$res) {

            $response = $this->getClient()->request('GET', $query);

            if ($response->getStatusCode() === 200) {

                $body = $response->getBody();
                $res  = json_decode($body, true);

                $this->getCache()->set($cacheName, $res);

            }

        }

        $this->setResult($res);

    }

    /**
     * get the guzzle client
     *
     * @return void
     */
    private function getClient()
    {
        if (empty($this->client)) {
            $this->setClient();
        }
        return $this->client;
    }

    /**
     * set the guzzle client
     *
     * @return void
     */
    private function setClient()
    {
        $this->client = new Client([
            'base_uri' => getenv('API_ENDPOINT'),
        ]);
        return $this;
    }

}
