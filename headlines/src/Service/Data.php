<?php

namespace App\Service;

use \GuzzleHttp\Client;

class Data extends Base
{

    private $client;



    public function getSingle(string $slug, $type) {

        $response = $this->getClient()->request('GET', 'item/' . $slug);

        
        
        if ($response->getStatusCode() === 200) {

            $body = $response->getBody();
            $data = json_decode($body, true);
            $this->setResult($data);

        }

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
