<?php

namespace Headline;


class Base {

    private $container;

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

}
