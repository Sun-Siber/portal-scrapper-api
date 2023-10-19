<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
class RepublikaService
{
  const ENDPOINTS = [
    'search' => 'https://p.republika.co.id/ando/search/',
    'read'   => 'https://p.republika.co.id/ando/read/'
  ];

  private Client $client;

  public function __construct(Client $client)
  {
    $this->client = $client;
  }

  public function search(string $keyword)
  {
    if ($keyword == null) throw new Exception("Error Processing Request", 1);

    $endpoint = self::ENDPOINTS['search'] . $keyword;

    try {
      $request  = $this->client->request('GET', $endpoint);
      $response = json_decode($request->getBody());
      $results = [];

      foreach ($response->items as $item) {
        $article = $this->getArticle($item->post_id);
        $results[] = $article;
      }

      return $results;
    } catch (Exception $exception) {
      //throw $th;

      dd($exception->getMessage());
    }

  }

  private function getArticle(string $guid)
  {
    $endpoint = self::ENDPOINTS['read'] . $guid;
    $request  = $this->client->request('GET', $endpoint);
    $response = json_decode($request->getBody());

    return $response[0];
    // return $response->result;
  }
}
