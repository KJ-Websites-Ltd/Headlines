<?php

namespace App\Service;



class Page extends Base 
{

    /**
     * generate the content and tags required to outline a page
     *
     * @return void
     */
    public function generateData()
    {

        $item = $this->getSingleItem();
        $res  = [];

        if (!empty($item)) {

            $content = $item->getContent();
            $tag     = $item->getTag();


            if (!empty($content)) {
                foreach ($content as $c) {
                    $res['content'][$c->getType()->getData()] = $c->getData();
                }
            }

            if (!empty($tag)) {
                foreach ($tag as $t) {
                    $res['tag'][$t->getType()->getData()] = $t->getData();
                }
            }


        }

        return $res;
    }

   

}
