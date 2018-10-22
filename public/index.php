<?php

require '../vendor/autoload.php';

use Graby\Graby;

$query = urlencode($_GET['q']);
$url = 'https://gapi.xyz/api/V1/?q='.$query.'&lang=en';
$feed = file_get_contents($url);
$data = json_decode($feed);
file_put_contents('../data/data.json', $feed);

//print_r($feed);
//$data = json_decode(file_get_contents('../data/data.json'));

$graby = new Graby();
$results = [];

echo '<pre>';
foreach($data->content as $item) {

    $news = [];
    $news['title'] = $item->title;
    $news['pubdate'] = strtotime($item->date);
    if (!empty($item->thumbnail)) {
        $news['image'] = $item->thumbnail[0];
    }
    $news['link'] = $item->link;
    $news['author'] = $item->author;
    $news['website'] = $item->website;
    
    $longContent = $graby->fetchContent($item->link);

    $news['html'] = $longContent['html'];
    $news['summary'] = $longContent['summary'];

    if (!empty($longContent['open_graph'])) {
        if (isset($longContent['open_graph']['og_image'])) {
            $news['image'] = $longContent['open_graph']['og_image'];
        }
    }

    $res[$news['link']] = $news;

}

echo '<pre>';
print_r($res);




