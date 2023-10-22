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
    $results = [];

    foreach ($links as $link) {
      $url = $link->getAttribute('href');
      $segment = explode('/', $url);
      $id = $segment[4]. '/' . $segment[5]. '/' . $segment[6]. '/' . $segment[7]. '/' . $segment[8]. '/' . $segment[9];

      $articles = $this->getArticle($id);
      $results[] = $articles;
    }

    return $results;
  }

  public function getArticle($id) {
    $response = $this->client->request('GET', "services.okezone.com/android/apps_detail_v2/{$id}");
    $body = json_decode($response->getBody());

    return $body[0];

    // return $body->article;
  }
}
