<?php

namespace Headline\Service;


class Base extends \Headline\Base {

    private $query;
    private $result;



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
