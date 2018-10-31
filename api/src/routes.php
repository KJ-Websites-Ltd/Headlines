<?php

use Slim\Http\Request;
use Slim\Http\Response;




// Return api data
$app->get('/api/', '\Headline\Controller\Api:getMultiple');
$app->get('/api/{s}/{e}/[{q}]', '\Headline\Controller\Api:getMultiple');
$app->get('/api/item/[{q}]', '\Headline\Controller\Api:getSingle');
$app->get('/api/tag/[{q}]', '\Headline\Controller\Api:getTag');

// create data
$app->get('/api/aggregate/[{q}]', '\Headline\Controller\Aggregate:setData');

