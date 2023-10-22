<?php

use App\Http\Controllers\Api\PortalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('antara', [PortalController::class, 'antara']);
Route::get('cnbc', [PortalController::class, 'cnbc']);
Route::get('cnn', [PortalController::class, 'cnn']);
Route::get('kompas', [PortalController::class, 'kompas']);
Route::get('tirto', [PortalController::class, 'tirto']);
Route::get('republika', [PortalController::class, 'republika']);
Route::get('okezone', [PortalController::class, 'okezone']);
Route::get('idn-times', [PortalController::class, 'idnTimes']);
Route::get('vivanews', [PortalController::class, 'vivanews']);

Route::get('test', function (Client $client) {
  // $response = $client->get('https://books.toscrape.com/');
  $response = $client->get('https://search.okezone.com/searchsphinx/loaddata/article/gibran/0');
  $htmlString = (string) $response->getBody();

  // dd($htmlString);

  libxml_use_internal_errors(true);
  $doc = new DOMDocument();
  $doc->loadHTML($htmlString);
  $xpath = new DOMXPath($doc);

  $titles = $xpath->evaluate('//div[@class="title"]/a');

  // dd($xpath);
  // dd($links);
  // dd($doc);
  $extractedTitles = [];
  foreach ($titles as $title) {
  $extractedTitles[] = $title->getAttribute('href');
  // echo $title->textContent.PHP_EOL;
  }

  dd($extractedTitles);

});
