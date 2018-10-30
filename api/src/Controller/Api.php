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
            $apiService->getMultiple(24);
        } else {
            $apiService->findMultipleByTag(24, $args['q']);
        }

        return $response->withJson($apiService->getResult(), 200);

    }


   

   

}
