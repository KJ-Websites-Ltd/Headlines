<?php

namespace App\Controller;

use App\Service\Advert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
    private function getData()
    {

        $simonWeston = '<a href="https://htalentmanagement.com/client/simon-weston" target="_blank">Click here to book Simon Weston CBE Motivational and Inspirational Keynote Speaker for your next event</a>';
        $henriLeconte = '<a href="https://htalentmanagement.com/client/henri-leconte" target="_blank">Click here to book Henri LeConte tennis legend for your next event</a>';
        $frankieDettori = '<a href="https://htalentmanagement.com/client/frankie-dettori" target="_blank">Click here to book Frankie Dettori legendary jockey for your next event</a>';
        $ericLandlard = '<a href="https://htalentmanagement.com/client/eric-lanlard" target="_blank">Click here to book French pâtissier Eric Lanlard for your next event</a>';
        $kjWebsites = '<a href="https://kjwebsites.co.uk" target="_blank">Affordable, Quality Web Design and Development for Shoreham by Sea, Lancing and Worthing</a>';
        $capitalMarketsIntelligence = '<a href="https://www.capital-markets-intelligence.com/" target="_blank">Capital Markets Intelligence publishes a comprehensive range of titles covering the international financial markets.</a>';
        $backInBrighton = '<a href="http://www.backinbrighton.co.uk/" target="_blank">Brighton Osteopathy</a>';
        $hConcertsEvents = '<a href="https://hconcertsandevents.com/" target="_blank">H Concerts and Events Creation, Promotion and Staging</a>';
        $eForex = '<a href="https://e-forex.net/" target="_blank">e-Forex Magazine - Transforming global foreign exhange markets</a>';
        $fxAlgonews = '<a href="https://fxalgonews.com/" target="_blank">FX AlgoNews Magazine - Algorithmic Foreign Exchange news and opinion</a>';
        $redBackReporter = '<a href="https://redbackreporter.com/" target="_blank">The Redback Reporter Magazine - Bringing you news, commentary and analysis about the chinese renminbi</a>';


        $data = [
            [
                'title'   => 'simon weston',
                'content' => $simonWeston,
            ],
            [
                'title'   => 'henri leconte',
                'content' => $henriLeconte
            ],
            [
                'title'   => 'frankie dettori',
                'content' => $frankieDettori,
            ],
            [
                'title'   => 'adur',
                'content' => $kjWebsites,
            ],
            [
                'title'   => 'wordpress',
                'content' => $kjWebsites,
            ],
            [
                'title'   => 'responsive web design',
                'content' => $kjWebsites,
            ],
            [
                'title'   => 'symfony',
                'content' => $kjWebsites,
            ],
            [
                'title'   => 'laravel',
                'content' => $kjWebsites,
            ],
            [
                'title'   => 'reactjs',
                'content' => $kjWebsites,
            ],
            [
                'title'   => 'worthing',
                'content' => $kjWebsites,
            ],
            [
                'title'   => 'brighton',
                'content' => $kjWebsites,
            ],
            [
                'title'   => 'capital markets',
                'content' => $capitalMarketsIntelligence,
            ],
            [
                'title'   => 'eric lanlard',
                'content' => $ericLandlard,
            ],
            [
                'title'   => 'osteopathy',
                'content' => $backInBrighton,
            ],
            [
                'title'   => 'corporate events',
                'content' => $hConcertsEvents,
            ],
            [
                'title'   => 'festival',
                'content' => $hConcertsEvents,
            ],
            [
                'title'   => 'cfh clearing',
                'content' => $eForex,
            ],
            [
                'title'   => 'forexclear',
                'content' => $eForex,
            ],
            [
                'title'   => 'fx global code',
                'content' => $eForex,
            ],
            [
                'title'   => 'blockchain',
                'content' => $eForex,
            ],
            [
                'title'   => 'algorithmic fx',
                'content' => $fxAlgonews,
            ],
            [
                'title'   => 'renminbi',
                'content' => $redBackReporter,
            ],
        ];

        return $data;

    }
}
