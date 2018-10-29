<?php

namespace Headline\Service\Source;

class Newsriver extends \Headline\Base
{

    const uri    = 'https://api.newsriver.io/v2/search?query=text%3A';
    const endUri = '&sortBy=_score&sortOrder=DESC&limit=15';
    const key    = 'sBBqsGXiYgF0Db5OV5tAw4k8tpkcKBIfTyZTOHG1wjmItLkS2CJ0AHXNsgfMrsI5n2pHZrSf1gT2PUujH1YaQA';

    public function getData(string $query)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::uri . $query . self::endUri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: ' . self::key,
        ));

        $data = curl_exec($ch);
        curl_close($ch);
        $res = [];

        if (!empty($data)) {

            $data = json_decode($data, true);

            foreach ($data as $i => $item) {

                $res[$i]['title']   = $item['title'];
                $res[$i]['link']    = $item['url'];
                $res[$i]['slug']    = $item['id'];
                $res[$i]['website'] = $item['website']['domainName'];
                $res[$i]['pubdate'] = strtotime($item['discoverDate']);
                $res[$i]['image']   = null;
                $res[$i]['author']  = null;
                $res[$i]['summary'] = $item['text'];
                $res[$i]['html']    = $item['structuredText'];

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
