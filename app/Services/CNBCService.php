<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;

class CNBCService
{
  /**
   *
   */
  const ENDPOINTS = [
    'search' => 'https://apps.cnbcindonesia.com/api/search',
    'read'   => 'https://apps.cnbcindonesia.com//api/news_detail',
  ];

  /**
   * Undocumented variable
   *
   * @var string
   */
  private $token = 'Basic Y25iY2luZG9uZXNpYTpjbmJjMW5kMG4zczE0';

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
   * @param integer $limit
   * @return void
   */
  public function search(string $keyword, int $page)
  {
    if ($keyword == null || $page == 0) throw new Exception("Error Processing Request");

    $this->keyword = $keyword;

    $results  = [];
    $endpoint = self::ENDPOINTS['search'] . "?query={$keyword}&page={$page}";
    $request  = $this->client->request('GET', $endpoint, [
      'headers' => [
        'Authorization' => $this->token
      ],
    ]);
    $response = json_decode($request->getBody());
    $articles = $response->items;

    return $articles;

    // foreach ($articles as $article)
    // {
    //   if ($article->namakanal == 'Foto News') continue;

    //   $results[] = $this->getArticle($article->url);
    // }

    // return $results;
  }

  /**
   * Undocumented function
   *
   * @param string $url
   * @return void
   */
  private function getArticle(string $url)
  {
    $endpoint = self::ENDPOINTS['read'] . "?url={$url}";
    $request  = $this->client->request('GET', $endpoint, [
      'headers' => [
        'Authorization' => $this->token
      ],
    ]);
    $response = json_decode($request->getBody());
    $content  = $response->content;

    return [
      'title'   => $content->titles->title,
      'keyword' => $this->keyword,
      'date'    => $content->dateset->created,
      'content' => $content->data,
    ];
  }

  /**
   * Undocumented function
   *
   * @param string $keyword
   * @return void
   */
  public function setKeyword(string $keyword)
  {
    $this->keyword = $keyword;
  }
}
