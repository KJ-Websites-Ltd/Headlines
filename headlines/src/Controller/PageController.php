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
    public function index($slug = 'home', Data $data, Page $page)
    {   

        $page->getSingle($slug, 1);
        $data->getMultiple();    
        

        return $this->render('page/base.html.twig', [
            'page' => $page->getResult(),
            'data' => $data->getResult()
        ]);
    }

    /**
     * Matches /tag/* 
     * 
     * @Route("/tag/{slug}", name="tag")
     */
    public function tag($slug='', Data $data, Page $page) {

        $page->getSingle('tag', 1);
        $data->getMultiple($slug);


        if (empty($page->getResult())) {
            throw $this->createNotFoundException('The page does not exist');
        }

        return $this->render('page/base.html.twig', [
            'page' => $page->getResult(),
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
