<?php

namespace Headline\Service;

class Api extends Base
{

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
     * Undocumented function
     *
     * @param integer $limit
     *
     * @return void
     */
    public function getMultiple($limit = 10)
    {

        $cacheName = 'api/get_multiple/' . $limit;
        $cache     = $this->getCacheObject($cacheName);
        $data      = $cache['res'];

        if (!$data) {

            $data = $this->getContainer()->get('headlineModelType')->getItemCollection(self::newsItemType, $limit);

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
     * Undocumented function
     *
     * @param integer $limit
     * @param [type] $query
     *
     * @return void
     */
    public function findMultipleByTag($limit = 10, $query)
    {

        $cacheName = 'api/find_multiple_by_tag/' . $limit . '/' . $query;
        $cache     = $this->getCacheObject($cacheName);
        $data      = $cache['res'];

        if (!$data) {

        $data = $this->getContainer()->get('headlineModelTag')->getItemCollection($query);

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
     * Undocumented function
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
