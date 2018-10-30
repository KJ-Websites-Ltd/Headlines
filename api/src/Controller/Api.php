<?php

namespace Headline\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class Api extends \Headline\Base
{

    const multipleLimit = 24;


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
     * return an array of available  tags 
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return void
     */
    public function getTag(Request $request, Response $response, array $args) {

        $apiService = $this->getContainer()->get('headlineServiceApi');
        $type = $args['t'];
        if (empty($type)) {
            $type =self::queryType;
        }

        $apiService->getTag($args['q'], $type);
        

        return $response->withJson($apiService->getResult(), 200);



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
            $apiService->getMultiple(self::multipleLimit);
        } else {
            $apiService->findMultipleByTag(self::multipleLimit, $args['q']);
        }

        return $response->withJson($apiService->getResult(), 200);

    }


   

   

}
