<?php

namespace Headline\Service;


class Base extends \Headline\Base {

    const newsItemType = 3;
    const htmlType = 7;
    const summaryType = 8;
    const imageType = 9;
    const authorType = 4;
    const websiteType = 6;
    const queryType = 5;

    private $query;
    private $result;

    
    public function getSingle(string $slug) {
        return null;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery(string $query)
    {
        $this->query = trim(urlencode($query));
        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult(array $result)
    {
        $this->result = $result;
        return $this;
    }
    


    

}
