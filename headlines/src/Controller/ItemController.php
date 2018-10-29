<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Data;
use App\Service\Page;

class ItemController extends AbstractController
{
    /**
     * Matches /item/* 
     * @Route("/item/{slug}", name="item")
     */
    public function index($slug, Data $data)
    {
        
        $data->getSingle($slug, null);
        $page = $data->getResult();

        
        
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
            'tag' => $page['tag'],
            'page' => $page
        ]);
    }
}
