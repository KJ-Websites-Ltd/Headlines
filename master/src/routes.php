<?php

use Slim\Http\Request;
use Slim\Http\Response;


//front end
$app->get('/[{q}]', '\Headline\Controller\Publish:getMultiple');
$app->get('/item/[{q}]', '\Headline\Controller\Publish:getSingle');


// Return api data
$app->get('/api/[{q}]', '\Headline\Controller\Api:getMultiple');
$app->get('/api/item/[{q}]', '\Headline\Controller\Api:getSingle');

// create data
$app->get('/api/aggregate/[{q}]', '\Headline\Controller\Api:aggregateData');


