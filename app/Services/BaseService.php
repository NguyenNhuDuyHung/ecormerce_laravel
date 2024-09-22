<?php

namespace App\Services;

use App\Services\Interfaces\BaseServiceInterface;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Str;

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
    public function __construct(RouterRepository $routerRepository)
    {
        $this->routerRepository = $routerRepository;
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

    public function formatRouterPayload($model, $request, $controllerName, $languageId)
    {
        $router = [
            'canonical' => Str::slug($request->input('canonical')),
            'module_id' => $model->id,
            'language_id' => $languageId,
            'controllers' => 'App\Http\Controllers\Frontend\\' . $controllerName . '',
        ];
        return $router;
    }

    public function createRouter($model, $request, $controllerName, $languageId)
    {
        $router = $this->formatRouterPayload($model, $request, $controllerName, $languageId);
        $this->routerRepository->create($router);
    }

    public function updateRouter($model, $request, $controllerName, $languageId)
    {
        $payload = $this->formatRouterPayload($model, $request, $controllerName, $languageId);
        $condition = [
            ['module_id', '=', $model->id],
            ['controllers', '=', 'App\Http\Controllers\Frontend\\' . $controllerName . ''],
        ];
        $router = $this->routerRepository->findByCondition($condition);
        $response = $this->routerRepository->update($router->id, $payload);

        return $response;
    }

    public function formatJson($request, $inputName)
    {
        return $request->input($inputName) && !empty($request->input($inputName))
            ? json_encode($request->input($inputName)) : '';
    }

    public function updateStatus($post = [])
    {
        DB::beginTransaction();
        try {
            $model = lcfirst($post['model']) . 'Repository';
            $payload[$post['field']] = (($post['value'] == 1) ? 2 : 1);
            $this->{$model}->update($post['modelId'], $payload);
            // $this->changeUserStatus($post, $payload[$post['field']]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();
            die();
            return false;
        }
    }

    public function updateStatusAll($post)
    {
        DB::beginTransaction();
        try {
            $model = lcfirst($post['model']) . 'Repository';
            $field = $post['field'];
            $payload = [$field => $post['value'] == 1 ? 2 : 1];
            $flag = $this->{$model}->updateByWhereIn('id', $post['ids'], $payload);
            // $this->changeUserStatus($post, $post['value']);
            // $this->changeUserStatus($post, $post['value']);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();
            die();
            return false;
        }
    }
}