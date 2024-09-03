<?php

namespace App\Services;

use App\Services\Interfaces\{Module}ServiceInterface;

use App\Repositories\Interfaces\{Module}RepositoryInterface as {Module}Repository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;

/**
 * Class {Module}Service
 * @package App\Services
 */
class {Module}Service extends BaseService implements {Module}ServiceInterface
{
    protected ${module}Repository;
    protected $nestedSet;
    protected $language;
    protected $routerRepository;
    public function __construct({Module}Repository ${module}Repository, RouterRepository $routerRepository)
    {
        $this->{module}Repository = ${module}Repository;
        $this->routerRepository = $routerRepository;
        $this->controllerName = '{Module}Controller';
    }

    private function paginateSelect()
    {
        return [
            '{tableName}.id',
            '{tableName}.publish',
            '{tableName}.image',
            '{tableName}.level',
            '{tableName}.order',
            'tb2.name',
            'tb2.canonical',
        ];
    }

    private function payload()
    {
        return ['parent_id', 'follow', 'publish', 'image', 'album'];
    }

    private function payloadLanguage()
    {
        return [
            'name',
            'description',
            'content',
            'meta_title',
            'meta_description',
            'meta_keyword',
            'canonical'
        ];
    }

    public function paginate($request, $languageId)
    {
        $perpage = $request->integer('perpage');
        $condition = [
            'keyword' => addcslashes($request->input('keyword'), '\\%_'),
            'publish' => $request->integer('publish'),
            'where' => [
                ['tb2.language_id', '=', $languageId],
            ]
        ];

        ${module}s = $this->{module}Repository
            ->pagination(
                $this->paginateSelect(),
                $condition,
                $perpage,
                ['path' => 'post/catalogue/index'],
                [
                    '{tableName}.lft',
                    'ASC'
                ],
                [
                    ['{pivotTableName}_language as tb2', 'tb2.{foreignKey}', '=', '{tableName}.id'],
                ],
                ['languages'],
            );
        return ${module}s;
    }

    private function createCatalogue($request)
    {
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();

        $payload['album'] = $this->formatAlbum($request);
        ${module} = $this->{module}Repository->create($payload);

        return ${module};
    }
    private function updateLanguageForCatalogue(${module}, $request, $languageId)
    {
        $payload = $this->formatLanguagePayload($request, ${module}, $languageId);
        ${module}->languages()->detach([$languageId, ${module}->id]);
        $response = $this->{module}Repository->createPivot(${module}, $payload, 'languages');
        return $response;
    }

    private function formatLanguagePayload($request, ${module}, $languageId)
    {
        $payload = $request->only($this->payloadLanguage());
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] = $languageId;
        $payload['{foreignKey}'] = ${module}->id;
        return $payload;
    }

    private function updateCatalogue(${module}, $request)
    {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($payload);
        $flag = $this->{module}Repository->update(${module}->id, $payload);
        return $flag;
    }

    public function create(Request $request, $languageId)
    {
        DB::beginTransaction();
        try {
            ${module} = $this->createCatalogue($request);

            if (${module}->id > 0) {
                $this->updateLanguageForCatalogue(${module}, $request, $languageId);

                $this->createRouter(${module}, $request, $this->controllerName, $languageId);

                $this->nestedSet = new Nestedsetbie([
                    'table' => '{tableName}',
                    'foreignkey' => '{foreignKey}',
                    'language_id' => $languageId,
                ]);

                $this->nestedset();
            }

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

    public function update($id, Request $request, $languageId)
    {
        DB::beginTransaction();
        try {

            ${module} = $this->{module}Repository->findById($id);
            $flag = $this->updateCatalogue(${module}, $request);
            if ($flag) {
                $this->updateLanguageForCatalogue(${module}, $request, $languageId);

                $this->updateRouter(${module}, $request, $this->controllerName, $languageId);

                $this->nestedSet = new Nestedsetbie([
                    'table' => '{tableName}',
                    'foreignkey' => '{foreignKey}',
                    'language_id' => $languageId,
                ]);

                $this->nestedset();
            }



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

    public function destroy($id, $languageId)
    {
        DB::beginTransaction();
        try {
            $this->{module}Repository->forceDelete($id);

            $this->nestedSet = new Nestedsetbie([
                'table' => '{tableName}',
                'foreignkey' => '{foreignKey}',
                'language_id' => $languageId,
            ]);

            $this->nestedSet->Get('level ASC', 'order ASC');
            $this->nestedSet->Recursive(0, $this->nestedSet->Set());
            $this->nestedSet->Action();
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
            $this->{module}Repository->update($post['modelId'], $payload);
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
            $flag = $this->{module}Repository->updateByWhereIn('id', $post['ids'], $payload);
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

    private function changePostStatus($post, $value)
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
            $this->{module}Repository->updateByWhereIn('id', $array, $payload);
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
