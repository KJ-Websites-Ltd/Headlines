<?php

namespace Headline\Service;

class Publish extends Base
{



    public function getSingle($slug) {


        $data = $this->getContainer()->get('headlineModelItem')->getSingleBySlug($slug, 3);
        if (!empty($data)) {
            $data = $this->generateFeatures($data);
        }

        $this->setResult($data);

    }




    /**
     * Undocumented function
     *
     * @param integer $limit
     *
     * @return void
     */
    public function getMultiple($limit=10)
    {

        $data = $this->getContainer()->get('headlineModelType')->getItemCollection(3, $limit);

        

        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = $this->generateFeaturesMultiple($v);
            }
        }

        $this->setResult($data);


    }


    /**
     * Undocumented function
     *
     * @param integer $limit
     * @param [type] $query
     *
     * @return void
     */
    public function findMultipleByTag($limit=10, $query) {

        $data = $this->getContainer()->get('headlineModelTag')->getItemCollection($query);

        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = $this->generateFeaturesMultiple($v);
            }
        }

        $this->setResult($data);


    }


    /**
     * Undocumented function
     *
     * @param [type] $item
     *
     * @return void
     */
    public function generateFeatures($item)
    {

        $item['summary'] = $this->getSummaryContent($item['id']);
        $item['html'] = $this->getHtmlContent($item['id']);
        $item['image']   = $this->getImageContent($item['id']);
        $item['author']   = $this->getAuthorContent($item['id']);
        $item['website']   = $this->getWebsiteContent($item['id']);

        return $item;

    }


    /**
     * Undocumented function
     *
     * @param [type] $item
     *
     * @return void
     */
    public function generateFeaturesMultiple($item)
    {

        $item['summary'] = $this->getSummaryContent($item['id']);
        $item['image']   = $this->getImageContent($item['id']);
        //$item['author']   = $this->getAuthorContent($item['id']);
        $item['website']   = $this->getWebsiteContent($item['id']);

        return $item;

    }

    /**
     * @brief return the html content for a item
     * @details [long description]
     * @return [description]
     */
    public function getHtmlContent($id)
    {
        $res = $this->getContainer()->get('headlineModelContent')->getData($id, 1);
        return $res;
    }

    /**
     * @brief return the summary content for a page
     * @details [long description]
     * @return [description]
     */
    public function getSummaryContent($id)
    {
        $res = $this->getContainer()->get('headlineModelContent')->getData($id, 2);

        return $res;
    }

    /**
     * @brief return the summary content for a page
     * @details [long description]
     * @return [description]
     */
    public function getImageContent($id)
    {
        $res = $this->getContainer()->get('headlineModelContent')->getData($id, 3);

        return $res;
    }

    /**
     * @brief return the summary content for a page
     * @details [long description]
     * @return [description]
     */
    public function getAuthorContent($id)
    {
        $res = $this->getContainer()->get('headlineModelTag')->getDataByItemAndType($id, 4);

        return $res;
    }

    /**
     * @brief return the summary content for a page
     * @details [long description]
     * @return [description]
     */
    public function getWebsiteContent($id)
    {
        $res = $this->getContainer()->get('headlineModelTag')->getDataByItemAndType($id, 6);
        if (!empty($res)) {
            $res = reset($res);
        }

        return $res;
    }

}
