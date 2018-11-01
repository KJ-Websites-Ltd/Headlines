<?php

namespace App\Service;

use \App\Entity\Content;
use \App\Entity\Item;
use \App\Entity\Type;

class Advert extends Base
{

    /**
     * add a single advert to the db
     *
     * @param [type] $data
     * @param boolean $flush
     *
     * @return void
     */
    public function addSingle($data, $flush = true)
    {

        $em = $this->getEm();

        $advert  = new Item();
        $link    = new Content();
        $pubDate = time();

        $advertType = $em->getRepository(Type::class)->find(4);
        $linkType   = $em->getRepository(Type::class)->find(5);

        $link->setData($data['content']);
        $link->setCreatedAt(1);
        $link->setUpdatedAt(1);
        $link->setType($linkType);

        $advert->setTitle($data['title']);
        //$page->setSlug('home');
        $advert->setActive(1);
        $advert->setCreatedAt($pubDate);
        $advert->setUpdatedAt($pubDate);
        $advert->setType($advertType);
        $advert->addContent($link);

        $em->persist($link);
        $em->persist($advert);
        if ($flush) {
            $em->flush();
        }

    }

    /**
     * the adverts are matched to item query tags by title, allow these to be deleted
     *
     * @param [type] $title
     *
     * @return void
     */
    private function updateSingleByTitle($data)
    {

        $em     = $this->getEm();
        $advert = $em->getRepository(Item::class)->findOneBy([
            'title' => $data['title'],
            'type'  => 4,
        ]);

        if (!empty($advert)) {
            foreach ($advert->getContent() as $content) {
                $em->remove($content);
            }
            $em->remove($advert);
        }

    }

    /**
     * loop through an array of data to create multiple adverts
     *
     * @param [type] $data
     *
     * @return void
     */
    public function updateMultiple($data)
    {

        $em = $this->getEm();

        if (!empty($data)) {
            foreach ($data as $d) {
                $this->updateSingleByTitle($d);
            }
            $em->flush();
            $this->addMultiple($data);
        }
    }

    /**
     * get a single advert by title
     *
     * @param [type] $title
     *
     * @return void
     */
    public function getSingleByTitle($title)
    {

        $cacheName = 'advert.get_single_by_title' . urlencode($title);
        $res       = $this->getCache()->get($cacheName);

        if (!$res) {

            $advert = $this->getEm()->getRepository(Item::class)->findOneByTitleAndType($title, 4);
            $res    = [];

            if (!empty($advert)) {
                $this->setSingleItem($advert);
                $res['data'] = $advert->getContent()[0]->getData();

                $this->getCache()->set($cacheName, $res);
            }

        }

        $this->setResult($res);

    }

}
