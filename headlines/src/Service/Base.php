<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use \App\Entity\Content;
use \App\Entity\Item;
use \App\Entity\Type;

class Base
{

    private $em;
    private $singleItem;
    private $result;

    public function __construct(EntityManagerInterface $em)
    {
        $this->setEm($em);

    }

    /**
     * return a complete single page object as an array
     *
     * @param [type] $slug
     *
     * @return void
     */
    public function getSingle($slug, $type)
    {

        $page = $this->getEm()->getRepository(Item::class)->findOneBySlugAndType($slug, $type);
        $res           = [];

        if (!empty($page)) {
        $this->setSingleItem($page);

        //default data for all items
       
        $res['title']  = $page->getTitle();
        $res['slug']   = $page->getSlug();
        $res['active'] = $page->getActive();

        $res['created_at'] = new \DateTime();
        $res['created_at']->setTimestamp($page->getCreatedAt());

        $res['updated_at'] = new \DateTime();
        $res['updated_at']->setTimestamp($page->getCreatedAt());

        $data = $this->generateData();

        if (!empty($data)) {
            foreach ($data as $type => $d) {
                $res[$type] = $d;
            }
        }
    }

       $this->setResult($res);

    }

    /**
     * extend this for each different type of item
     *
     * @return void
     */
    public function generateData()
    {
        return null;
    }

    /**
     * create page used for dev, remove or update
     *
     * @return void
     */
    public function addSingle($data)
    {

        $em = $this->em;

        $page    = new Item();
        $content = new Content();
        $pubDate = time();


        $pageType    = $em->getRepository(Type::class)->find(1);
        $contentType = $em->getRepository(Type::class)->find(2);

        $content->setData($data['content']);
        $content->setCreatedAt(1);
        $content->setUpdatedAt(1);
        $content->setType($contentType);

        $page->setTitle($data['title']);
        //$page->setSlug('home');
        $page->setActive(1);
        $page->setCreatedAt($pubDate);
        $page->setUpdatedAt($pubDate);
        $page->setType($pageType);
        $page->addContent($content);

        $em->persist($content);
        $em->persist($page);
        $em->flush();
    

    }

    /**
     * return the entity manager
     *
     * @return void
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * set the entity manager
     *
     * @param \Doctrine\ORM\EntityManager $em
     *
     * @return void
     */
    public function setEm(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
        return $this;
    }

    public function getSingleItem()
    {
        return $this->singleItem;
    }

    public function setSingleItem(Item $item)
    {
        $this->singleItem = $item;
        return $this;
    }

    public function getResult() {
        return $this->result;
    }

    public function setResult(array $result) {
        $this->result = $result;
        return $this;
    }

}
