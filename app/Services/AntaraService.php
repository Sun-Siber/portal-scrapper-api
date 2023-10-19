<?php

namespace App\Services;

use App\Helpers\HTMLTagsCleaner;
use Exception;
use GuzzleHttp\Client;

class AntaraService
{
  /**
   *
   */
  const BASEURL = 'https://megapolitan.antaranews.com/api/api.php';

  /**
   *
   */
  const KEY = 'e5bbc6c9421f5e129281aab838c9f029';

  /**
   *
   */
  const ACTIONS = [
    'search' => 'get_news',
    'read'   => 'read_news',
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
   * @return void
   */
  public function search(string $keyword)
  {
    if ($keyword == null) throw new Exception("Error Processing Request");

    $this->keyword = $keyword;

    $results  = [];
    $request  = $this->client->request('GET', self::BASEURL, [
      'query' => [
        'key'     => self::KEY,
        'action'  => self::ACTIONS['search'],
        'keyword' => $keyword
      ]
    ]);
    $articles = json_decode($request->getBody());

    return $articles;
  }

  /**
   * Undocumented function
   *
   * @param string $url
   * @return void
   */
  public function getArticle(string $url)
  {
    $request  = $this->client->request('GET', $url);
    $response = json_decode($request->getBody());
    $content  = $response[0];

    return $content;
  }
}
