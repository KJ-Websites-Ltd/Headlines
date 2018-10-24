<?php

use Slim\Http\Request;
use Slim\Http\Response;



// Return data
$app->get('/[{q}]', '\Headline\Controller\Api:getMultiple');
$app->get('/item/[{q}]', '\Headline\Controller\Api:getSingle');



// create data
$app->get('/aggregate/[{q}]', '\Headline\Controller\Api:aggregateData');


