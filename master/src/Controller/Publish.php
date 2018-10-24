<?php

namespace Headline\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class Publish extends \Headline\Base
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

    }

    /**
     * return multiple items from the api based on a query or latest n number of items
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return void
     */
    public function getMultiple(Request $request, Response $response, array $args)
    {

        $publishService = $this->getContainer()->get('headlineServicePublish');
        if (empty($args['q'])) {
            $publishService->getMultiple(5);
        } else {
            $publishService->findMultipleByTag(5, $args['q']);
        }

        return $this->getContainer()->view->render($response, 'index.html.twig', [
            'data' => $publishService->getResult(),
        ]);

        //return $this->getContainer()->renderer->render($response, 'index.phtml', $args);


    }

}
