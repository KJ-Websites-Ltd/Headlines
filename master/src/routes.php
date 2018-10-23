<?php

use Slim\Http\Request;
use Slim\Http\Response;



// Routes
$app->get('/[{q}]', function (Request $request, Response $response, array $args) {

    $publishService = $this->get('headlineServicePublish');

    if (empty($args['q'])) {
        $publishService->getMultiple(5);
    } else {
        $publishService->findMultipleByTag(5, $args['q']);
    }
    
    return $response->withJson($publishService->getResult(), 200);

});


$app->get('/item/[{q}]', function (Request $request, Response $response, array $args) {

    $res = $response->withRedirect('/');
    $publishService = $this->get('headlineServicePublish');

    if (!empty($args['q'])) {
        
        $publishService->getSingle($args['q']);
        $res = $response->withJson($publishService->getResult(), 200);  

    }


    return $res;

});




$app->get('/aggregate/[{q}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    //$this->logger->info("Slim-Skeleton '/' route");
    //$this->headlineServiceBase->test();
    //print_r($args);

    // Render index view
    //return $this->renderer->render($response, 'index.phtml', $args);

    
    $aggregatorService = $this->get('headlineServiceAggregate');
    $aggregatorService->setQuery($args['q']);
    $aggregatorService->getData();
    return $response->withJson($aggregatorService->getResult(), 200);
    
    

});


