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


        $data = $collectData->test();
       

        return $this->json($collectData->test());

       //echo $collectData->test();

        /*return $this->render('base.html.twig', [
            'slug' => $slug,
        ]);*/
    }
}
