<?php

namespace App\Services;

use App\Services\Interfaces\PostCatalogueServiceInterface;

use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
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
 * Class PostCatalogueService
 * @package App\Services
 */
class PostCatalogueService extends BaseService implements PostCatalogueServiceInterface
{
    protected $postCatalogueRepository;
    protected $nestedSet;
    protected $language;
    protected $routerRepository;
    public function __construct(PostCatalogueRepository $postCatalogueRepository, RouterRepository $routerRepository)
    {
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->routerRepository = $routerRepository;
        $this->controllerName = 'PostCatalogueController';
    }

    private function paginateSelect()
    {
        return [
            'post_catalogues.id',
            'post_catalogues.publish',
            'post_catalogues.image',
            'post_catalogues.level',
            'post_catalogues.order',
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

        $postCatalogues = $this->postCatalogueRepository
            ->pagination(
                $this->paginateSelect(),
                $condition,
                $perpage,
                ['path' => 'post/catalogue/index'],
                [
                    'post_catalogues.lft',
                    'ASC'
                ],
                [
                    ['post_catalogue_language as tb2', 'tb2.post_catalogue_id', '=', 'post_catalogues.id'],
                ],
                ['languages'],
            );
        return $postCatalogues;
    }

    private function createCatalogue($request)
    {
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();

        $payload['album'] = $this->formatAlbum($request);
        $postCatalogue = $this->postCatalogueRepository->create($payload);

        return $postCatalogue;
    }
    private function updateLanguageForCatalogue($postCatalogue, $request, $languageId)
    {
        $payload = $this->formatLanguagePayload($request, $postCatalogue, $languageId);
        $postCatalogue->languages()->detach([$languageId, $postCatalogue->id]);
        $response = $this->postCatalogueRepository->createPivot($postCatalogue, $payload, 'languages');
        return $response;
    }

    private function formatLanguagePayload($request, $postCatalogue, $languageId)
    {
        $payload = $request->only($this->payloadLanguage());
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] = $languageId;
        $payload['post_catalogue_id'] = $postCatalogue->id;
        return $payload;
    }

    private function updateCatalogue($postCatalogue, $request)
    {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($payload);
        $flag = $this->postCatalogueRepository->update($postCatalogue->id, $payload);
        return $flag;
    }

    public function create(Request $request, $languageId)
    {
        DB::beginTransaction();
        try {
            $postCatalogue = $this->createCatalogue($request);

            if ($postCatalogue->id > 0) {
                $this->updateLanguageForCatalogue($postCatalogue, $request, $languageId);

                $this->createRouter($postCatalogue, $request, $this->controllerName);

                $this->nestedSet = new Nestedsetbie([
                    'table' => 'post_catalogues',
                    'foreignkey' => 'post_catalogue_id',
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

            $postCatalogue = $this->postCatalogueRepository->findById($id);
            $flag = $this->updateCatalogue($postCatalogue, $request);
            if ($flag) {
                $this->updateLanguageForCatalogue($postCatalogue, $request, $languageId);

                $this->updateRouter($postCatalogue, $request, $this->controllerName);

                $this->nestedSet = new Nestedsetbie([
                    'table' => 'post_catalogues',
                    'foreignkey' => 'post_catalogue_id',
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
            $this->postCatalogueRepository->forceDelete($id);

            $this->nestedSet = new Nestedsetbie([
                'table' => 'post_catalogues',
                'foreignkey' => 'post_catalogue_id',
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
            $this->postCatalogueRepository->update($post['modelId'], $payload);
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
            $flag = $this->postCatalogueRepository->updateByWhereIn('id', $post['ids'], $payload);
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
            $this->postCatalogueRepository->updateByWhereIn('id', $array, $payload);
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
