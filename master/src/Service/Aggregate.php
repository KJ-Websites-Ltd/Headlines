<?php

namespace Headline\Service;

use Graby\Graby;

class Aggregate extends Base
{

    const sourceCollection = ['newsriver', 'gapi'];
    const sourceBaseName   = 'headlineServiceSource';

    private $source;
    private $graby;

    /**
     * Undocumented function
     *
     * @return void
     */
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

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getLongData()
    {

        $res = $this->getResult();

        if (!empty($res)) {

            foreach ($res as $i => $item) {

                $longData = $this->getGraby()->fetchContent($item['link']);

                if ((int) $longData['status'] === 200) {

                    if (empty($res[$i]['html'])) {
                        $res[$i]['html'] = $longData['html'];
                    }
                    if (empty($res[$i]['summary'])) {
                        $res[$i]['summary'] = $longData['summary'];
                    }

                    if (!empty($longData['open_graph'])) {
                        if (isset($longData['open_graph']['og_image'])) {
                            $res[$i]['image'] = $longData['open_graph']['og_image'];
                        }
                    }

                }

            }

            $this->setResult($res);
            $this->saveResult();

        }

    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function saveResult()
    {

        $res = $this->getResult();
        if (!empty($res)) {

            foreach ($res as $item) {

                $i               = [];
                $i['title']      = $item['title'];
                $i['created_at'] = $item['pubdate'];
                $i['link']       = $item['link'];
                $i['active']     = 1;

                $html    = $item['html'];
                $summary = $item['summary'];
                $author  = $item['author'];
                $image   = $item['image'];
                $website = $item['website'];

                $id = $this->getContainer()->get('headlineModelItem')->postSingle($i, 3);

                if ($id > 0) {
                    $this->getContainer()->get('headlineModelContent')->postSingle($html, 1, $id);
                    $this->getContainer()->get('headlineModelContent')->postSingle($summary, 2, $id);
                    $this->getContainer()->get('headlineModelContent')->postSingle($image, 3, $id);

                    if (is_array($author)) {
                        $this->getContainer()->get('headlineModelTag')->postMultiple(json_encode($author), 4, $id);
                    } else {
                        $this->getContainer()->get('headlineModelTag')->postSingle($author, 4, $id);
                    }
                    
                    $this->getContainer()->get('headlineModelTag')->postSingle($this->getQuery(), 5, $id);
                    
                    if (!empty($website)) {
                        $this->getContainer()->get('headlineModelTag')->postSingle($website, 6, $id);
                    }
                    

                    $this->getContainer()->get('headlineModelType')->postItem2Type($id, 3);
                }

            }

        }

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
