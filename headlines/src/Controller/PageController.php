<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use App\Service\Data;
use App\Service\Page;

class PageController extends AbstractController
{
    /**
     * Matches /* 
     * 
     * @Route("/{slug}", name="page")
     */
    public function pageAction($slug = 'home', Data $data, Page $page)
    {   

        $page->getSingle($slug, 1);
       
        //if (!$page->checkBot()) {
           // $template = 'page/react.html.twig';
       // } else {
            $template = 'page/home.html.twig';
            $pageTemplate = 'page/'. $slug .'.html.twig';
            if ($this->get('twig')->getLoader()->exists($pageTemplate)) {
                $template = $pageTemplate;
            }
      //  }
    

        switch($slug) {

            case 'home':
            $data->getMultiple();
            break;

            case 'tag':
            $data->getMultipleTag();
            break;

        }
        

        return $this->render($template, [
            'controller_name' => 'PageController',
            'page' => $page->getResult(),
            'tag'  => 'latest stories',
            'data' => $data->getResult()
        ]);
    }

    /**
     * Matches /tag/* 
     * 
     * @Route("/tag/{slug}", name="tag")
     */
    public function tagAction($slug='', Data $data, Page $page) {

        $page->getSingle('tag', 1);
        $data->getMultiple('0/50/' . $slug);
        


        if (empty($page->getResult())) {
            throw $this->createNotFoundException('The page does not exist');
        }

        return $this->render('page/tags.html.twig', [
            'controller_name' => 'PageController',
            'page' => $page->getResult(),
            'tag'  => $slug,
            'advert' => $data->getAdvert($slug),
            'data' => $data->getResult()
        ]);

    } 


    




    /**
     * Matches /create
     * This is just used to create pages whilst in development, replace this with fixutres
     * @Route("/create/", name="create")
     *
     * @param Page $page
     *
     * @return void
     */
    /*public function create(Page $page) {


        //setup a simple page array to create pages with
        $data = [];
        $data['title'] = 'this is a tag page for';
        $data['content'] = 'this is just some content';

        $page->addSingle($data);

        return 'done';



    }*/

}
