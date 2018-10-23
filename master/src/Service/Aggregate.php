<?php

namespace Headline\Service;

use Graby\Graby;

class Aggregate extends Base
{

    const sourceCollection = ['gapi'];
    const sourceBaseName   = 'headlineServiceSource';

    private $query;
    private $source;
    private $result;
    private $graby;

    public function getData()
    {

        //pick a random source
        $source = self::sourceCollection;
        $source = self::sourceCollection[array_rand(self::sourceCollection)];
        //get the result
        $result = $this->getContainer()->get(self::sourceBaseName . ucwords($source))->getData($this->getQuery());

        $this->setResult($result);
        $this->getLongData();

    }

    public function getLongData()
    {

        $res = $this->getResult();

        if (!empty($res)) {

            foreach ($res as $i => $item) {

                $longData = $this->getGraby()->fetchContent($item['link']);

                if ((int) $longData['status'] === 200) {

                    $res[$i]['html']    = $longData['html'];
                    $res[$i]['summary'] = $longData['summary'];

                    if (!empty($longContent['open_graph'])) {
                        if (isset($longContent['open_graph']['og_image'])) {
                            $res[$i]['image'] = $longContent['open_graph']['og_image'];
                        }
                    }

                }

            }

            $this->setResult($res);

        }

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

    public function getSource()
    {
        return $this->source;
    }

    public function setSource(string $source)
    {
        $this->source = $source;
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

    public function getGraby()
    {
        if (empty($this->graby)) {
            $this->setGraby(new Graby());
        }
        return $this->graby;
    }

    public function setGraby(\Graby\Graby $graby)
    {
        $this->graby = $graby;
        return $this;
    }

}
