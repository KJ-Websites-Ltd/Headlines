<?php

namespace App\Service;

use \App\Service\Advert;


class Data extends Base
{
    
    
    
    /**
     * return a single news item by slug and type
     *
     * @param string $slug
     * @param [type] $type
     *
     * @return void
     */
    public function getSingle(string $slug, $type)
    {
        
        $cacheName = 'data.get_single_'.urlencode($slug).'_'.$type;
        $cacheItem = $this->getUtilService()->getCacheItem($cacheName);
        
        if ($cacheItem->isHit()) {
            $res = $cacheItem->get();
        } else {
            
            $response = $this->getUtilService()->getClient()->request('GET', 'item/'.$slug);
            
            if ($response->getStatusCode() === 200) {
                
                $body          = $response->getBody();
                $res           = json_decode($body, true);
                $res['advert'] = $this->getAdvert($res['tag']);
                
                $this->getUtilService()->setCacheItem($cacheItem, $res);
                
            }
            
        }
        
        $this->setResult($res);
        
    }
    
    
    
    /**
     * get an advert text from the db
     *
     * @param string $tag
     *
     * @return void
     */
    public function getAdvert(string $tag)
    {
        
        $cacheName = 'data.get_advert_'.$tag;
        $cacheItem = $this->getUtilService()->getCacheItem($cacheName);
        
        if ($cacheItem->isHit()) {
            
            $res = $cacheItem->get();
            
        } else {
            
            $advert = new Advert($this->getEm());
            $advert->getSingleByTitle($tag);
            $advert = $advert->getResult();
            $res    = null;
            
            if ( ! empty($advert['data'])) {
                $res = $advert['data'];
                
                //save to the cache
                $this->getUtilService()->setCacheItem($cacheItem, $res);
            }
            
        }
        
        return $res;
        
    }
    
    /**
     * get multiple tags from the api
     *
     * @return void
     */
    public function getMultipleTag()
    {
        
        $cacheName = 'data.get_multiple_tag';
        $cacheItem = $this->getUtilService()->getCacheItem($cacheName);
        
        if ($cacheItem->isHit()) {
            $res = $cacheItem->get();
        } else {
            
            $response = $this->getUtilService()->getClient()->request('GET', 'tag/all');
            
            if ($response->getStatusCode() === 200) {
                
                $body = $response->getBody();
                $res  = json_decode($body, true);
                
                $this->getUtilService()->setCacheItem($cacheItem, $res);
                
            }
            
        }
        
        $this->setResult($res);
        
    }
    
    /**
     * get multiple news items from the api based on tag query
     *
     * @param string  $query
     * @param integer $limit
     *
     * @return void
     */
    public function getMultiple(string $query = '', $limit = 10)
    {
        
        $cacheName = 'data.get_multiple_'.urlencode($query).$limit;
        $cacheItem = $this->getUtilService()->getCacheItem($cacheName);
        
        if ($cacheItem->isHit()) {
            $res = $cacheItem->get();
        } else {
            
            $response = $this->getUtilService()->getClient()->request('GET', $query);
            
            if ($response->getStatusCode() === 200) {
                
                $body = $response->getBody();
                $res  = json_decode($body, true);
                
                //save to the cache
                $this->getUtilService()->setCacheItem($cacheItem, $res);
                
            }
            
        }
        
        $this->setResult($res);
        
    }
    
}
