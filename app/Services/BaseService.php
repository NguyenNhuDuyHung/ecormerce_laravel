<?php

namespace App\Services;

use App\Services\Interfaces\BaseServiceInterface;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class BaseService
 * @package App\Services
 */
class BaseService implements BaseServiceInterface
{

    protected $routerRepository;
    protected $controllerName;
    protected $nestedSet;
    public function __construct( RouterRepository $routerRepository)
    {
        $this->routerRepository = $routerRepository;
    }

    public function currentLanguage()
    {
        return 1;
    }

    public function formatAlbum($payload)
    {
        return (isset($payload['album']) && !empty($payload['album']))
            ? json_encode($payload['album']) : '';
    }

    public function nestedset()
    {
        $this->nestedSet->Get('level ASC', 'order ASC');
        $this->nestedSet->Recursive(0, $this->nestedSet->Set());
        $this->nestedSet->Action();
    }

    public function formatRouterPayload($model, $request, $controllerName)
    {
        $router = [
            'canonical' => $request->input('canonical'),
            'module_id' => $model->id,
            'controllers' => 'App\Http\Controllers\Frontend\\' . $controllerName . '',
        ];
        return $router;
    }

    public function createRouter($model, $request, $controllerName)
    {
        $router = $this->formatRouterPayload($model, $request, $controllerName);
        $this->routerRepository->create($router);
    }

    public function updateRouter($model, $request, $controllerName)
    {
        $payload = $this->formatRouterPayload($model, $request, $controllerName);
        $condition = [
            ['module_id', '=', $model->id],
            ['controllers', '=', 'App\Http\Controllers\Frontend\\' . $controllerName . ''],
        ];
        $router = $this->routerRepository->findByCondition($condition);
        $response = $this->routerRepository->update($router->id, $payload);

        return $response;
    }
}
