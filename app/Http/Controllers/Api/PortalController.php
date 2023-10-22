<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AntaraService;
use App\Services\CNBCService;
use App\Services\CNNService;
use App\Services\IDNTimesService;
use App\Services\KompasService;
use App\Services\RepublikaService;
use App\Services\TirtoService;
use Illuminate\Http\Request;
use App\Services\OkezoneService;
use App\Services\VivanewsService;

class PortalController extends Controller
{
  public function antara(AntaraService $service, Request $request)
  {
    $keyword = $request->query('keyword');
    $result = $service->search($keyword);

    return response()->json($result, 200);
  }

  public function cnbc(CNBCService $service, Request $request)
  {
    $keyword = $request->query('keyword');
    $page = $request->query('page');
    $result = $service->search($keyword, $page);

    return response()->json($result, 200);
  }

  public function cnn(CNNService $service, Request $request)
  {
    $keyword = $request->query('keyword');
    $page = $request->query('page');
    $result = $service->search($keyword, $page);

    return response()->json($result, 200);
  }

  public function kompas(KompasService $service, Request $request)
  {
    $keyword = $request->query('keyword');
    $page = $request->query('page');
    $limit = $request->query('limit');
    $result = $service->search($keyword, $page, $limit);

    return response()->json($result, 200);
  }

  public function tirto(TirtoService $service, Request $request)
  {
    $keyword = $request->query('keyword');
    $page = $request->query('page');
    $limit = $request->query('limit');
    $result = $service->search($keyword, $page, $limit);

    return response()->json($result, 200);
  }

  public function republika(RepublikaService $service, Request $request)
  {
    $keyword = $request->query('keyword');
    $result = $service->search($keyword);

    return response()->json($result, 200);
  }

  public function okezone(OkezoneService $service, Request $request)
  {
    $keyword = $request->query('keyword');
    $result = $service->search($keyword);

    return response()->json($result, 200);
  }

  public function idnTimes(IDNTimesService $service, Request $request)
  {
    $keyword = $request->query('keyword');
    $result = $service->search($keyword);

    return response()->json($result, 200);
  }

  public function vivanews(VivanewsService $service, Request $request)
  {
    $keyword = $request->query('keyword');
    $page = $request->query('page');
    $limit = $request->query('limit');
    $result = $service->search($keyword, $page, $limit);

    return response()->json($result, 200);
  }
}
