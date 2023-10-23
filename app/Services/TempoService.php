<?php

namespace App\Services;

libxml_use_internal_errors(true);

use App\Helpers\HTMLTagsCleaner;
use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;

class TempoService {

  private $client;

  public function __construct(Client $client) {
    $this->client = $client;
  }
  public function search($keyword, $page = 1, $limit = 12) {
    $response = $this->client->request('GET', "https://www.tempo.co/search?q={$keyword}");
    $html = (string) $response->getBody();
    $document = new DOMDocument();
    $document->loadHTML($html);
    $xpath = new DOMXPath($document);
    $links = $xpath->evaluate('//h2[@class="title"]/a');
    $results = [];

    // foreach ($links as $link) {
    //   $url = $link->getAttribute('href');
    //   $segment = explode('/', $url);
    //   $id = (int) $segment[4];
    //   // dd($url);

    //   $articles = $this->getArticle($url);

    //   dd($articles);
    //   // $results[] = ;
    // }

    $articles = $this->getArticle('1787256');

    // dd(json_decode($articles));

    return $results;
  }

  public function getArticle($id) {
    $response = $this->client->request('GET', 'https://bisnis.tempo.co/read/1787255/ada-kata-nusantara-dalam-visi-misi-anies-muhaimin-merujuk-ke-pembangunan-ikn');
    $body = (string) $response->getBody();

    // dd($body);

    // $document = new DOMDocument();
    // $document->loadHTML($body);
    // $xpath = new DOMXPath($document);
    // $titleNode = $xpath->query('//h1[@class="title margin-bottom-sm"]');
    // $title = $titleNode[$titleNode->length - 1]->nodeValue;

    $title = $this->getTextNode($body, '//h1[@class="title margin-bottom-sm"]');
    $reporter = [
      'name' => $this->getTextNode($body, '//span[@itemprop="author"]'),
      'link' => $this->getAttrNode($body, '//div[@class="box-avatar margin-right-sm"]/div/p[@class="title bold"]/a'),
    ];
    $editor = [
      'name' => $this->getTextNode($body, '//span[@itemprop="editor"]'),
      'link' => $this->getAttrNode($body, '//div[@class="box-avatar"]/div/p[@class="title bold"]/a'),
    ];
    $published_date = $this->getTextNode($body, '//p[@itemprop="datePublished"]');
    $image = [
      'source' => $this->getAttrNode($body, '//div[@class="foto-detail margin-bottom-sm"]/figure/img', 'src'),
      'caption' => $this->getTextNode($body, '//div[@class="foto-detail margin-bottom-sm"]/figcaption'),
    ];
    $content = HTMLTagsCleaner::cleaner((string) $this->getTextNode($body, '//div[@class="detail-konten"]'));

    // dd($reporter);
    // dd($title);

    $result = [
      'url' => $id,
      'title' => $title,
      'reporter' => $reporter,
      'editor' => $editor,
      'image' => $image,
      'content' => $content,
      'published_date' => $published_date,
    ];

    dd($result);


    // return $body;
  }

  public function getTextNode($body, $path) {
    $document = new DOMDocument();
    $document->loadHTML($body);
    $xpath = new DOMXPath($document);
    $node = $xpath->query($path);
    $text = $node[$node->length - 1]->nodeValue;

    return $text;
  }

  public function getAttrNode($body, $path, $attr = 'href') {
    $document = new DOMDocument();
    $document->loadHTML($body);
    $xpath = new DOMXPath($document);
    $node = $xpath->query($path);
    $text = $node[$node->length - 1]->getAttribute($attr);

    return $text;
  }

  public function setDocument($body) {
    $document = new DOMDocument();
    $document->loadHTML($body);

    return new DOMXPath($document);
  }
}
