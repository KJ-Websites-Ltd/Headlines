<?php

namespace Headline;


class Base {

    const newsItemType = 3;
    const htmlType = 7;
    const summaryType = 8;
    const imageType = 9;
    const authorType = 4;
    const websiteType = 6;
    const queryType = 5;


    private $container;
    private $cachePool;
    private $disableCache = true;

    public function __construct($container) {
        $this->setContainer($container);
    }

    /**
     * get the slim container instance
     *
     * @return void
     */
    public function getContainer() {
        return $this->container;
    }

    /**
     * set the slim container instance
     *
     * @param [type] $container
     *
     * @return void
     */
    public function setContainer($container) {
        $this->container = $container;
        return $this;
    }

    /**
     * return the statsch cache pool object
     *
     * @return void
     */
    public function getCachePool()
    {
        if (empty($this->cachePool)) {
            $this->setCachePool();
        }
        return $this->cachePool;
    }

    /**
     * set the stash cache pool object up
     *
     * @return void
     */
    public function setCachePool()
    {

        // Uses a install specific default path if none is passed.
        $options         = array('path' => '../cache/');
        $driver          = new \Stash\Driver\Sqlite($options);
        $this->cachePool = new \Stash\Pool($driver);
        return $this;

    }

     /**
     * return a named cache object if it exists
     *
     * @param [type] $name
     * @return void
     */
    public function getCacheObject($name)
    {

        $res = null;
        $cache = null;


        if ($this->getDisableCache() !== true) {


            $cache = $this->getCachePool()->getItem($name);
            $res = $cache->get();

            if ($cache->isMiss()) {
                $res = null;
                $cache->lock();
            }

        }

        return ['res' => $res, 'cache' => $cache];

    }

    /**
     * set a cache object
     *
     * @param [type] $obj
     * @return void
     */
    public function setCacheObject($obj, $cache)
    {

        if ($this->getDisableCache() !== true) {
            $this->getCachePool()->save($cache->set($obj));
        }
        return $this;

    }

    private function getDisableCache() {

        if ($this->disableCache !== true) {
            $this->setDisableCache();
        }

       return $this->disableCache;


    }

    private function setDisableCache() {
        
        $res = false;

        /*if ($this->getContainer()['settings']['environment'] === 'development' ) {
            $res = true;
        } else if ($this->getContainer()['settings']['disableCache'] === true) {
            $res = true;
        }*/

        if ($res === true) {
            //clear the entire cache its cheap to rebuild
            system('rm -rf ' . escapeshellarg('../cache/'), $retval);
        }

        $this->disableCache = $res;
    }



}
