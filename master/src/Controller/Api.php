<?php

namespace Headline\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class Api extends \Headline\Base
{

    


     /**
     * return a  single item based on its slug
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
        $apiService = $this->getContainer()->get('headlineServiceApi');

        if (!empty($args['q'])) {

            $apiService->getSingle($args['q']);
            $res = $response->withJson($apiService->getResult(), 200);

        }

        return $res;

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

        $apiService = $this->getContainer()->get('headlineServiceApi');

        if (empty($args['q'])) {
            $apiService->getMultiple(100);
        } else {
            $apiService->findMultipleByTag(100, $args['q']);
        }

        return $response->withJson($apiService->getResult(), 200);

    }


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

   

}
