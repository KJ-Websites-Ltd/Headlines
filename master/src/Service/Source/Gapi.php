<?php

namespace Headline\Service\Source;

class Gapi extends \Headline\Base
{

    const uri = 'https://gapi.xyz/api/V1/?lang=en&q=';

    
    public function getData(string $query)
    {

        $data = file_get_contents(self::uri . $query);
        $data = json_decode($data, true);
        $res  = [];

        if ((int) $data['count_results'] > 0) {

            $data = $data['content'];

            foreach ($data as $i => $item) {

                //ensure the results returned are nomalised
                $res[$i]['title']   = $item['title'];
                $res[$i]['link']    = $item['link'];
                $res[$i]['website'] = $item['website'];
                $res[$i]['pubdate'] = strtotime($item['date']);
                $res[$i]['image']   = null;
                $res[$i]['author']  = null;

                //arrays
                if (!empty($item['thumbnail'])) {
                    $res[$i]['image'] = $item['thumbnail'][0];
                }

                if (!empty($item['author'])) {
                    $res[$i]['author'] = $item['author'];
                }

            }

        }

        return $res;

    }

}
