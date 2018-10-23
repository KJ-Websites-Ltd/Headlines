<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/aggregate/[{q}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    //$this->logger->info("Slim-Skeleton '/' route");


    //$this->headlineServiceBase->test();

    //print_r($args);
 

    //return $response->withJson(['1' => '1'], 200);

    //return 'hello';

    // Render index view
    //return $this->renderer->render($response, 'index.phtml', $args);

    $aggregatorService = $this->get('headlineServiceAggregate');
    $aggregatorService->setQuery($args['q']);
    $aggregatorService->getData();


    return $response->withJson($aggregatorService->getResult(), 200);

});
