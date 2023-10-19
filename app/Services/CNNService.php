<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;

class CNNService
{
  /**
   *
   */
  const BASEURL = 'https://apps.cnnindonesia.com';

  /**
   *
   */
  const KEY = 'Q05ONG5kcm9pZDo0bmRybzFk';

  /**
   *
   */
  const ENDPOINTS = [
    'search' => '/api/search',
    'read'   => '/api/news_detail',
  ];

  /**
   * Undocumented variable
   *
   * @var Client
   */
  private $client;

  /**
   * Undocumented variable
   *
   * @var string
   */
  private $keyword;

  /**
   * Undocumented function
   *
   * @param Client $client
   */
  public function __construct(Client $client)
  {
    $this->client = $client;
  }

  /**
   * Undocumented function
   *
   * @param string $keyword
   * @param integer $page
   * @return void
   */
  public function search(string $keyword, int $page)
  {
    if ($keyword == null) throw new Exception("Error Processing Request");

    $this->keyword = $keyword;

    $results  = [];
    $endpoint = $this->getEndpoint('search');
    $request  = $this->client->request('GET', $endpoint, [
      'headers' => [
        'Authorization' => "Basic " . self::KEY
      ],
      'query' => [
        'query' => $keyword,
        'page'  => $page
      ]
    ]);
    $response = json_decode($request->getBody());
    $articles = $response->items;

    // return $articles;

    foreach ($articles as $article)
    {
      $results[] = $this->getArticle($article->url);
    }

    return $results;
  }

  /**
   * Undocumented function
   *
   * @param string $url
   * @return void
   */
  private function getArticle(string $url)
  {
    $endpoint = $this->getEndpoint('read');
    $request  = $this->client->request('GET', $endpoint, [
      'headers' => [
        'Authorization' => "Basic " . self::KEY
      ],
      'query' => [
        'url' => $url
      ]
    ]);
    $response = json_decode($request->getBody());

    return $response->content;

    // return [
    //   'title'   => $article->titles->title,
    //   'keyword' => $this->keyword,
    //   'date'    => $article->dateset->created,
    //   'content' => $article->data
    // ];
  }

  /**
   * Undocumented function
   *
   * @param string $type
   * @return void
   */
  private function getEndpoint(string $type)
  {
    return self::BASEURL . self::ENDPOINTS[$type];
  }
}
