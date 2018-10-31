<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Advert;



class AdvertController extends AbstractController
{
    /**
     * @Route("/advert", name="advert")
     */
    public function advertAction(Advert $advert)
    {


        $advert->updateMultiple($this->getData());

        return $this->render('advert/index.html.twig', [
            'controller_name' => 'AdvertController',
        ]);
    }

    /**
     * setup the data to use for adverts, these will be updated to the db
     *
     * @return void
     */
    private function getData() {

        $data = [
            [
            'title' => 'simon weston',
            'content' => '<a href="https://htalentmanagement.com/client/simon-weston" target="_blank">Click here to book Simon Weston CBE Motivation and Inspirational Keynote Speaker for your next event</a>'
            ],
            [
            'title' => 'henri leconte',
            'content' => '<a href="https://htalentmanagement.com/client/henri-leconte" target="_blank">Click here to book Henri LeConte tennis legend for your next event</a>'
            ]
            ];




        return $data;

    }
}
