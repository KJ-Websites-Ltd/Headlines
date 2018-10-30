<?php

namespace Headline\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class Aggregate extends \Headline\Base
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
    public function setData(Request $request, Response $response, array $args)
    {

        $aggregatorService = $this->getContainer()->get('headlineServiceAggregate');
        $aggregatorService->setQuery($args['q']);
        $aggregatorService->getData();
        return $response->withJson($aggregatorService->getResult(), 200);
    }

}
