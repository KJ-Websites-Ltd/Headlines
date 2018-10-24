<?php

namespace Headline\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class Api extends \Headline\Base
{

    /**
     * aggregate new data into the datastore based on a query
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return void
     */
    public function aggregateData(Request $request, Response $response, array $args)
    {

        $aggregatorService = $this->getContainer()->get('headlineServiceAggregate');
        $aggregatorService->setQuery($args['q']);
        $aggregatorService->getData();
        return $response->withJson($aggregatorService->getResult(), 200);

    }

    /**
     * return multiple items from the datastore based on a query or latest n number of items
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return void
     */
    public function getMultiple(Request $request, Response $response, array $args)
    {

        $publishService = $this->getContainer()->get('headlineServicePublish');

        if (empty($args['q'])) {
            $publishService->getMultiple(5);
        } else {
            $publishService->findMultipleByTag(5, $args['q']);
        }

        return $response->withJson($publishService->getResult(), 200);

    }

    /**
     * returna  single item based on its slug
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return void
     */
    public function getSingle(Request $request, Response $response, array $args)
    {

        $res            = $response->withRedirect('/');
        $publishService = $this->getContainer()->get('headlineServicePublish');

        if (!empty($args['q'])) {

            $publishService->getSingle($args['q']);
            $res = $response->withJson($publishService->getResult(), 200);

        }

        return $res;

    }

}
