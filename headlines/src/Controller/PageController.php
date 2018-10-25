<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CollectData;

class PageController extends AbstractController
{
    /**
     * Matches /page/* 
     * 
     * @Route("/{slug}", name="page")
     */
    public function index($slug = 'home', CollectData $collectData)
    {   

        $collectData->getMultiple();
        $data = $collectData->getData();


        //return $this->json($collectData->getData());

        return $this->render('base.html.twig', [
            //'page' => $page,
        ]);
    }
}
