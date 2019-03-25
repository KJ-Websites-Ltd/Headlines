<?php

namespace Headline\Service\Source;

class Gapi extends \Headline\Base
{
    
    const uri = 'https://gapi.xyz/api/v2/?token=692ea5c1db4bdd4ff71a3ab35cf710d4&lang=en&q=';

    
    public function getData(string $query)
    {

        $data = file_get_contents(self::uri . $query);
        $data = json_decode($data, true);
        $res  = [];
        
    
        if ((int) $data['count_results'] > 0) {

            $data = $data['articles'];

            foreach ($data as $i => $item) {

                //ensure the results returned are nomalised
                $res[$i]['title']   = $item['title'];
                $res[$i]['link']    = $item['link'];
                $res[$i]['website'] = $item['website'];
                $res[$i]['pubdate'] = strtotime($item['date']);
                $res[$i]['image']   = $item['image'];
                $res[$i]['author']  = null;
                $res[$i]['summary'] = $item['desc'];

            }

        }

        return $res;

    }

}
