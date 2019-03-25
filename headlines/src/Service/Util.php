<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use \GuzzleHttp\Client;

class Util
{
    
    const CACHE_NAME = 'app.cache';
    private $cache;
    private $client;
    
    
    /**
     * check if the current browser is a bot
     *
     * @return void
     */
    public function checkBot()
    {
        
        $botParser = new BotParser();
        $botParser->setUserAgent($this->getRequest()->headers->get('User-Agent'));
        $botParser->discardDetails();
        $result = $botParser->parse();
        
        return $result;
        
    }
    
    /**
     * retrieve a cache object
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getCacheItem(string $name)
    {
        $ci = $this->getCache()->getItem($name);
        $ci->expiresAfter((int)getenv('CACHE_EXPIRY'));
        
        return $ci;
        
    }
    
    public function getCache()
    {
        if (empty($this->cache)) {
            $this->setCache(new FilesystemAdapter(self::CACHE_NAME));
        }
        
        return $this->cache;
    }
    
    public function setCache(FilesystemAdapter $cache)
    {
        $this->cache = $cache;
        
        return $this;
    }
    
    /**
     * Set a cache object
     *
     * @param $cacheItem
     * @param $data
     *
     * @return $this
     */
    public function setCacheItem($cacheItem, $data)
    {
        $cacheItem->set($data);
        $this->getCache()->save($cacheItem);
        
        return $this;
    }
    
    /**
     * get the guzzle client
     *
     * @return void
     */
    public function getClient()
    {
        if (empty($this->client)) {
            $this->setClient();
        }
        
        return $this->client;
    }
    
    /**
     * set the guzzle client
     *
     * @return void
     */
    public function setClient()
    {
        
        $this->client = new Client(
            [
                'base_uri' => getenv('API_ENDPOINT'),
            ]
        );
        
        return $this;
    }
    
}
