<?php


namespace Headline\Service;

class Publish extends Base
{

    const apiUri = 'http://headline.kjwebsites.co.uk/api/';

    public function getSingle(string $slug) {

    }


    public function getMultiple($limit=10) {
 
        $this->getData(null);
        $this->setResult(array_slice($this->getResult(), 0, $limit));
       
    }

    /**
     * Undocumented function
     *
     * @param [type] $params
     *
     * @return void
     */
    private function getData($params) {

        try {
            $data = file_get_contents(self::apiUri . $params);
        } catch (Exception $e) {
            print_r($e);
        }
    
        
        if (!empty($data)) {
            $data = json_decode($data);
            $this->setResult($data);
        }

        

    }


    


}
