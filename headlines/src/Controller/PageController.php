<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Data;
use App\Service\Page;

class PageController extends AbstractController
{
    /**
     * Matches /page/* 
     * 
     * @Route("/{slug}", name="page")
     */
    public function index($slug = 'home', Data $data, Page $page)
    {   

        $page->getSingle($slug, 1);
        $data->getMultiple();

        print_r($data->getResult());
        

        return $this->render('base.html.twig', [
            'page' => $page->getResult(),
            'data' => $data->getResult()
        ]);
    }
}
