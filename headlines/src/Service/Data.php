<?php

namespace App\Service;

use \GuzzleHttp\Client;
use \App\Service\Advert;

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
    public function getSingle(string $slug, $type) {

        $response = $this->getClient()->request('GET', 'item/' . $slug);
        
        if ($response->getStatusCode() === 200) {

            $body = $response->getBody();
            $data = json_decode($body, true);
            $data['advert'] = $this->getAdvert($data['tag']);
            $this->setResult($data);

        }

    }


    public function getMultipleTag() {

        $response = $this->getClient()->request('GET', 'tag/all');

        if ($response->getStatusCode() === 200) {

            $body = $response->getBody();
            $data = json_decode($body, true);
                        
            $this->setResult($data);

        }


    }


    public function getAdvert(string $tag) {

        $advert = new Advert($this->getEm());
        $advert->getSingleByTitle($tag);
        $advert = $advert->getResult();
        $res = null;

        if (!empty($advert['data'])) {
            $res = $advert['data'];
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

        $response = $this->getClient()->request('GET', $query);

        if ($response->getStatusCode() === 200) {

            $body = $response->getBody();
            $data = json_decode($body, true);
            

            $this->setResult($data);

        }

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
