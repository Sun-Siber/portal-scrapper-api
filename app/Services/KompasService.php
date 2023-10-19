<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;

class KompasService
{
  CONST ENDPOINTS = [
    'search' => 'https://api.kompas.com/external/find.php',
    'read'   => 'https://api.kompas.com/2016/detail.php'
  ];

  /**
   * Undocumented variable
   *
   * @var Client
   */
  private $client;

  private $thumbs = [];

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
   * @param int $page
   * @param int $limit
   * @return void
   */
  public function search(string $keyword, int $page, int $limit)
  {
    if ($keyword == null || $page == 0 || $limit == 0) throw new Exception("Error Processing Request");

    $endpoint = self::ENDPOINTS['search'] . "?command={$keyword}&page={$page}&limit={$limit}";
    $results  = [];
    $request  = $this->client->request('GET', $endpoint);
    $response = json_decode($request->getBody());
    $articles = $response->xml->pencarian->item;

    foreach ($articles as $article)
    {
      $results[] = $this->getArticle($article->guid);
    }

    return $results;
  }

  /**
   * Undocumented function
   *
   * @param string $guid
   * @return void
   */
  private function getArticle(string $guid)
  {
    $endpoint = self::ENDPOINTS['read'] . "?guid={$guid}";
    $request  = $this->client->request('GET', $endpoint);
    $response = json_decode($request->getBody());

    return $response->result;
  }
}
