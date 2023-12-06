<?php

namespace App;

use Symfony\Component\DomCrawler\Crawler;

require 'vendor/autoload.php';

class Scrape
{
    private array $products = [];

    public function run(): void
    {
        $document = ScrapeHelper::fetchDocument('https://www.magpiehq.com/developer-challenge/smartphones');

            $nodeValues = $document->filter('.product')->each(function (Crawler $node, $i){
            $title = $node->text();
            $title = $node->filter('h3')->text();
            $price = $node->filter('.text-sm')->previousAll()->text();
            $image = $node->filter('img')->attr('src');
            $capacity = $node->filter('.product-capacity')->text();
            $colour = $node->filter('.bg-white')->last()->text();
            $availabilityText = str_replace('Availability:', '', $node->filter('.text-sm')->previousAll()->nextAll()->text());
            $isAvailable = str_contains($node->filter('.text-sm')->previousAll()->nextAll()->text(), 'In Stock') ? "true" : "false";
            $shippingText = $node->filter('.bg-white > div')->last()->text();
            $shippingDate = $node->filter('.bg-white')->text();
            

            $data = [
                'title' => $title,
                'price' => $price,
                'image' => $image,
                'capacity' => $capacity,
                'colour' => $colour,
                'availabilityText' => $availabilityText,
                'isAvailable' => $isAvailable,
                'shippingText' => $shippingText,

            ];

            print_r(json_encode($data, 200));
            //echo $title;
        });

        file_put_contents('output.json', json_encode($this->products));
    }
}

$scrape = new Scrape();
$scrape->run();
