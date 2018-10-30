<?php

use Slim\Http\Request;
use Slim\Http\Response;




// Return api data
$app->get('/api/[{q}]', '\Headline\Controller\Api:getMultiple');
$app->get('/api/item/[{q}]', '\Headline\Controller\Api:getSingle');

// create data
$app->get('/api/aggregate/[{q}]', '\Headline\Controller\Aggregate:setData');


