<?php

namespace App\Service;

use \GuzzleHttp\Client;

class CollectData
{

    private $client;
    private $data;

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
            $data = json_decode($body);

            $this->setData($data);

        }

    }

    private function getClient()
    {
        if (empty($this->client)) {
            $this->setClient();
        }
        return $this->client;
    }

    private function setClient()
    {
        $this->client = new Client([
            'base_uri' => getenv('API_ENDPOINT'),
        ]);
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    private function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

}
