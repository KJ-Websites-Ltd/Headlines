<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use DeviceDetector\Parser\Bot AS BotParser;
use Symfony\Component\HttpFoundation\Request;

use \App\Entity\Content;
use \App\Entity\Item;
use \App\Entity\Type;

class Base
{

    private $em;
    private $singleItem;
    private $result;
    private $request;

    public function __construct(EntityManagerInterface $em)
    {
        $this->setEm($em);
        $this->setRequest(Request::createFromGlobals());
    }

    /**
     * check if the current browser is a bot
     *
     * @return void
     */
    public function checkBot() {

        $botParser = new BotParser();
        $botParser->setUserAgent($this->getRequest()->headers->get('User-Agent'));
        $botParser->discardDetails();
        $result = $botParser->parse();

        return $result;


    }


    /*public function checkDevice() {

        DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $dd = new DeviceDetector($userAgent);
        $dd->parse();
        
        if ($dd->isBot()) {
            // handle bots,spiders,crawlers,...
            $botInfo = $dd->getBot();
          } else {
            $clientInfo = $dd->getClient(); // holds information about browser, feed reader, media player, ...
            $osInfo = $dd->getOs();
            $device = $dd->getDeviceName();
            $brand = $dd->getBrandName();
            $model = $dd->getModel();

            print_r($clientInfo);
          }

    }*/


    /**
     * return a complete single page object as an array
     *
     * @param [type] $slug
     *
     * @return void
     */
    public function getSingle(string $slug, $type)
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
    public function addSingle($data, $flush=true)
    {

        $em = $this->getEm();

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
        if ($flush) {
            $em->flush();
        }
        
    

    }



    /**
     * add multiple items
     *
     * @param [type] $data
     *
     * @return void
     */
    public function addMultiple($data) {

        if (!empty($data)) {

            $em = $this->getEm();

            foreach($data as $d) {
                $this->addSingle($d, false);
            }

            $em->flush();

        }


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

    public function getRequest() {
        return $this->request;
    }

    public function setRequest($request) {
        $this->request = $request;
        return $this;
    }

}
