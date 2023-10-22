<?php

namespace App\Services;

libxml_use_internal_errors(true);

use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;

class VivanewsService {

  private $client;

  public function __construct(Client $client) {
    $this->client = $client;
  }
  public function search($keyword, $page = 1, $limit = 12) {
    $response = $this->client->request('POST', "https://www.viva.co.id/request/load-more-search", [
      "form_params"=> [
        'keyword' => $keyword,
        'ctype' => 'art',
        'page' => $page,
        'record_count' => $limit,
        '_token' => 'oUYBOPQyWvG5AUIyR99H9kthI3162HRxEISQAzCd',
      ]
    ]);
    $html = (string) $response->getBody();
    $document = new DOMDocument();
    $document->loadHTML($html);
    $xpath = new DOMXPath($document);
    $links = $xpath->evaluate('//a[@class="article-list-thumb-link flex_ori"]');
    $results = [];

    foreach ($links as $link) {
      $url = $link->getAttribute('href');
      $segment = explode('/', $url);
      $articleSlug = end($segment);
      $id = explode('-', $articleSlug)[0];

      $articles = $this->getArticle($id);
      $results[] = $articles;
    }

    return $results;
  }

  public function getArticle($id) {
    $response = $this->client->request('GET', "api-public.viva.co.id/v/230/detail/id/{$id}");
    $body = json_decode($response->getBody());

    return $body->response->detail;
  }
}
