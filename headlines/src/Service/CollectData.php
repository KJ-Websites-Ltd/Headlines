<?php

namespace App\Service;

use \GuzzleHttp\Client;





class CollectData {

    public function test() {

        
        $client = new Client([
            'base_uri' => 'http://headlinesapi.kjwebsites.co.uk/api/'
        ]);
        
        $response = $client->request('GET', 'trump');

        $body = $response->getBody();
        $body = json_decode($body);

        //echo $body;
        

        return $body;

    }


}
