<?php

namespace App\Services;

use App\Helpers\HTMLTagsCleaner;
use Exception;
use GuzzleHttp\Client;

class TirtoService
{
  /**
   *
   */
  const ENDPOINTS = [
    'search' => 'https://ot.tirto.id/apps/search',
    'read'   => 'https://ot.tirto.id/apps/article/detail/'
  ];

  /**
   *
   */
  const TOKEN = 'Basic Z2FtbWE6cHJlbWl1bU9ubHk=';

  /**
   * Undocumented variable
   *
   * @var Client
   */
  private $client;

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
  public function search(string $keyword, int $page, int $limit)
  {
    if ($keyword == null || $page == 0 || $limit == 0) throw new Exception("Error Processing Request", 1);

    $results  = [];
    $endpoint = self::ENDPOINTS['search'] . "?q={$keyword}&page={$page}&limit={$limit}";
    $request  = $this->client->request('GET', $endpoint, [
      'headers' => [
        'Authorization' => self::TOKEN
      ],
    ]);

    $response = json_decode($request->getBody());
    $articles = $response->data;

    foreach ($articles as $article)
    {
      // $results[] = [
      //   'title'   => $article->judul,
      //   'keyword' => $keyword,
      //   'date'    => $article->date_created,
      //   'content' => HTMLTagsCleaner::cleaner($article->isi),
      // ];

      $results[] = $article;
    }

    return $results;
  }
}
