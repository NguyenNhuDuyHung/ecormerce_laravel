<?php

namespace App\Services;

use App\Services\Interfaces\LanguageServiceInterface;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Str;

/**
 * Class LanguageService
 * @package App\Services
 */
class LanguageService extends BaseService implements LanguageServiceInterface
{
    protected $languageRepository;
    protected $routerRepository;
    public function __construct(LanguageRepository $languageRepository, RouterRepository $routerRepository)
    {
        $this->routerRepository = $routerRepository;
        $this->languageRepository = $languageRepository;
    }

    private function paginateSelect()
    {
        return ['id', 'name', 'canonical', 'publish', 'image'];
    }

    public function paginate($request)
    {
        $condition['keyword'] = addcslashes($request->input('keyword'), '\\%_');
        $condition['publish'] = $request->integer('publish');
        $perpage = $request->integer('perpage');
        $languages = $this->languageRepository
            ->pagination($this->paginateSelect(), $condition, $perpage, ['path' => 'language/index']);
        return $languages;
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $payload['user_id'] = Auth::id();
            $this->languageRepository->create($payload);
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

    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $this->languageRepository->update($id, $payload);
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

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->languageRepository->forceDelete($id);
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

    public function updateStatus($post = [])
    {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = (($post['value'] == 1) ? 2 : 1);
            $language = $this->languageRepository->update($post['modelId'], $payload);
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
            $field = $post['field'];
            $payload = [$field => $post['value'] == 1 ? 2 : 1];
            $flag = $this->languageRepository->updateByWhereIn('id', $post['ids'], $payload);
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

    private function changelanguageStatus($post, $value)
    {
        DB::beginTransaction();
        try {
            $array = [];
            if (isset($post['modelId'])) {
                $array[] = $post['modelId'];
            } else {
                $array = $post['id'];
            }
            $payload[$post['field']] = $value;
            $this->languageRepository->updateByWhereIn('id', $array, $payload);
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

    public function switch($id)
    {

        DB::beginTransaction();
        try {
            $this->languageRepository->update($id, ['current' => 1]);
            $this->languageRepository->updateByWhere(
                [
                    ['id', '!=', $id],
                ],
                [
                    'current' => 0,
                ]
            );
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

    public function saveTranslate($option, $request)
    {

        DB::beginTransaction();
        try {
            $payload = [
                'name' => $request->input('translate_name'),
                'description' => $request->input('translate_description'),
                'content' => $request->input('translate_content'),
                'meta_title' => $request->input('translate_meta_title'),
                'meta_keyword' => $request->input('translate_meta_keyword'),
                'meta_description' => $request->input('translate_meta_description'),
                'canonical' => $request->input('translate_canonical'),
                $this->convertModelToField($option['model']) => $option['id'],
                'language_id' => $option['languageId'],
            ];
            $repositoryNamespace = 'App\Repositories\\' . ucfirst($option['model']) . 'Repository';
            if (class_exists($repositoryNamespace)) {
                $repositoryInstance = app($repositoryNamespace);
            }

            $model = $repositoryInstance->findById($option['id']);
            $model->languages()->detach([$option['languageId'], $model->id]);

            $repositoryInstance->createPivot($model, $payload, 'languages');

            $conditions = [
                ['module_id', '=', $option['id']],
                ['controllers', '=', 'App\Http\Controllers\Frontend\\' . ucfirst($option['model']) . 'Controller'],
                ['language_id', '=', $option['languageId']],
            ];
            $this->routerRepository->forceDeleteByCondition($conditions);

            $router = [
                'canonical' => Str::slug($request->input('translate_canonical')),
                'module_id' => $option['id'],
                'language_id' => $option['languageId'],
                'controllers' => 'App\Http\Controllers\Frontend\\' . ucfirst($option['model']) . 'Controller',
            ];
            $this->routerRepository->create($router);

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

    private function convertModelToField($model)
    {
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model));
        $temp .= '_id';
        return $temp;
    }
}
