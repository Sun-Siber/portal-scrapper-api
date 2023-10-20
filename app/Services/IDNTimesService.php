<?php

namespace App\Services;

libxml_use_internal_errors(true);

use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;

class IDNTimesService {

  private $client;

  public function __construct(Client $client) {
    $this->client = $client;
  }
  public function search($keyword, $page = 1) {
    $response = $this->client->get("https://www.idntimes.com/search?keyword={$keyword}&type=all&page={$page}");
    $html = (string) $response->getBody();
    $document = new DOMDocument();
    $document->loadHTML($html);
    $xpath = new DOMXPath($document);
    $result = [];

    $links = $xpath->evaluate('//li[@class="box-latest box-list"]/a');

    foreach ($links as $link) {
      $url = $link->getAttribute('href');
      $url = explode('/', $url);
      $id = "/{$url[3]}/$url[4]/$url[5]/$url[6]";

      $article = $this->getArticle($id);
      $result[] = $article;
    }

    return $result;
  }

  // mobile-api.idntimes.com/v2/article
  public function getArticle($id) {
    $response = $this->client->request('GET', "mobile-api.idntimes.com/v2/article{$id}", [
      'headers' => [
        'x-api-key' => '1ccc5bc4-8bb4-414c-b524-92d11a85a818'
      ]
    ]);
    $body = json_decode($response->getBody());

    return $body->article;
  }
}
