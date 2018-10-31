<?php

namespace Headline\Service;

use Graby\Graby;
use Headline\Service\Api;

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
     * pick a random tag to aggregate data for, run this via a cron job
     *
     * @return void
     */
    public function pluckData() {

        $api = new Api($this->getContainer());
        $api->getTag('', self::queryType);
        $tags = $api->getResult();
        $randomKey = array_rand($tags);
        $tag = $tags[$randomKey];

        $this->setQuery($tag);
        $this->getData();


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

                $query = urldecode($this->getQuery());

                //if the html doesnt include the query, then this is too general a piece of news
                $process = false;
                if (stripos($html, $query) !== false) {
                    $process = true;
                }


                if ($process === true) {

                $id = $this->getContainer()->get('headlineModelItem')->postSingle($i, self::newsItemType);

                if ($id > 0) {
                    if (!empty($html)) {
                        $this->getContainer()->get('headlineModelContent')->postSingle($html, self::htmlType, $id);
                    }
                    if (!empty($summary)) {
                        $this->getContainer()->get('headlineModelContent')->postSingle($summary, self::summaryType, $id);
                    }
                    if (!empty($image)) {
                        $this->getContainer()->get('headlineModelContent')->postSingle($image, self::imageType, $id);
                    }
                    

                    if (is_array($author)) {
                        $this->getContainer()->get('headlineModelTag')->postMultiple(json_encode($author), self::authorType, $id);
                    } else {
                        if (!empty($author)) {
                            $this->getContainer()->get('headlineModelTag')->postSingle($author, self::authorType, $id);
                        }
                        
                    }
                    
                    $this->getContainer()->get('headlineModelTag')->postSingle($query, self::queryType, $id);
                    
                    if (!empty($website)) {
                        $this->getContainer()->get('headlineModelTag')->postSingle($website, self::websiteType, $id);
                    }
                    

                    $this->getContainer()->get('headlineModelType')->postItem2Type($id, self::newsItemType);
                }

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
