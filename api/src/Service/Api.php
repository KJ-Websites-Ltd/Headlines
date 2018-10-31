<?php

namespace Headline\Service;

class Api extends Base
{

    /**
     * return a single item
     *
     * @param string $slug
     *
     * @return void
     */
    public function getSingle(string $slug)
    {

        $cacheName = 'api/get_single/' . $slug;
        $cache     = $this->getCacheObject($cacheName);
        $data      = $cache['res'];

        if (!$data) {

            $data = $this->getContainer()->get('headlineModelItem')->getSingleBySlug($slug, self::newsItemType);

            if (empty($data)) {
                $data = [];
            } else {
                $data = $this->generateFeatures($data);
                $this->setCacheObject($data, $cache['cache']);
            }

        }

        $this->setResult($data);

    }

    /**
     * return multiple items
     *
     * @param integer $limit
     *
     * @return void
     */
    public function getMultiple($start, $end)
    {

        $cacheName = 'api/get_multiple/' . $start . '/'. $end;
        $cache     = $this->getCacheObject($cacheName);
        $data      = $cache['res'];

        if (!$data) {

            $data = $this->getContainer()->get('headlineModelType')->getItemCollection(self::newsItemType, $start, $end);

            if (empty($data)) {
                $data = [];
            } else {
                foreach ($data as $k => $v) {
                    $data[$k] = $this->generateFeaturesMultiple($v);
                }

                $this->setCacheObject($data, $cache['cache']);

            }

        }

        $this->setResult($data);

    }


    /**
     * return an array of tags based on the type id provided
     *
     * @param string $query
     * @param [type] $type
     *
     * @return void
     */
    public function getTag(string $query, $type) {

        $cacheName = 'api/get_tag/' . $query .'/'. $type;
        $cache     = $this->getCacheObject($cacheName);
        $data      = $cache['res'];

        if (empty($data)) {

        $data = $this->getContainer()->get('headlineModelTag')->getDataByType($type);
        $res = [];
        if (!empty($data)) {
            foreach($data as $d) {
                $res[] = $d['data'];
            }
        }

        $this->setCacheObject($data, $cache['cache']);

        }


        $this->setResult($res);

    }




    /**
     * return multiple items by tag
     *
     * @param integer $limit
     * @param [type] $query
     *
     * @return void
     */
    public function findMultipleByTag($start, $end,  $query)
    {

        $cacheName = 'api/find_multiple_by_tag/' . $start . '/'. $end . '/' . $query;
        $cache     = $this->getCacheObject($cacheName);
        $data      = $cache['res'];

        if (!$data) {

            $data = $this->getContainer()->get('headlineModelTag')->getItemCollection($start, $end, $query);

            if (empty($data)) {
                $data = [];
            } else {
                foreach ($data as $k => $v) {
                    $data[$k] = $this->generateFeaturesMultiple($v);
                }
            }

            $this->setCacheObject($data, $cache['cache']);

        }

        $this->setResult($data);

    }

    /**
     * generate the features needed for a single item
     *
     * @param [type] $item
     *
     * @return void
     */
    public function generateFeatures($item)
    {

        $item['summary'] = $this->getSummaryContent($item['id']);
        $item['html']    = $this->getHtmlContent($item['id']);
        $item['image']   = $this->getImageContent($item['id']);
        $item['author']  = $this->getAuthorContent($item['id']);
        $item['website'] = $this->getWebsiteContent($item['id']);

        return $item;

    }

    /**
     * return the features needed for multiple items
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
        $item['website'] = $this->getWebsiteContent($item['id']);

        return $item;

    }

    

    /**
     * @brief return the html content for a item
     * @details [long description]
     * @return [description]
     */
    public function getHtmlContent($id)
    {
        $res = $this->getContainer()->get('headlineModelContent')->getData($id, self::htmlType);
        return $res;
    }

    /**
     * @brief return the summary content for a page
     * @details [long description]
     * @return [description]
     */
    public function getSummaryContent($id)
    {
        $res = $this->getContainer()->get('headlineModelContent')->getData($id, self::summaryType);

        return $res;
    }

    /**
     * @brief return the summary content for a page
     * @details [long description]
     * @return [description]
     */
    public function getImageContent($id)
    {
        $res = $this->getContainer()->get('headlineModelContent')->getData($id, self::imageType);

        return $res;
    }

    /**
     * @brief return the summary content for a page
     * @details [long description]
     * @return [description]
     */
    public function getAuthorContent($id)
    {
        $res = $this->getContainer()->get('headlineModelTag')->getDataByItemAndType($id, self::authorType);

        return $res;
    }

    /**
     * @brief return the summary content for a page
     * @details [long description]
     * @return [description]
     */
    public function getWebsiteContent($id)
    {
        $res = $this->getContainer()->get('headlineModelTag')->getDataByItemAndType($id, self::websiteType);
        if (!empty($res)) {
            $res = reset($res);
        }

        return $res;
    }

}
