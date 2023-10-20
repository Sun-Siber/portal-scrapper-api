<?php

namespace App\Services;

libxml_use_internal_errors(true);

use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;

class OkezoneService {

  private $client;

  public function __construct(Client $client) {
    $this->client = $client;
  }
  public function search($keyword, $page = 0) {
    $response = $this->client->get("https://search.okezone.com/searchsphinx/loaddata/article/{$keyword}/{$page}");
    $html = (string) $response->getBody();
    $document = new DOMDocument();
    $document->loadHTML($html);
    $xpath = new DOMXPath($document);
    $links = $xpath->evaluate('//div[@class="title"]/a');

    foreach ($links as $link) {
      $url = $link->getAttribute('href');

      dd($url);
    }
  }
}
