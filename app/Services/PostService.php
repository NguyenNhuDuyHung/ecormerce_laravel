<?php

namespace App\Services;

use App\Services\Interfaces\PostServiceInterface;

use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Class PostService
 * @package App\Services
 */
class PostService extends BaseService implements PostServiceInterface
{
    protected $postRepository;
    protected $language;
    protected $routerRepository;
    public function __construct(PostRepository $postRepository, RouterRepository $routerRepository)
    {
        $this->postRepository = $postRepository;
        $this->routerRepository = $routerRepository;
        $this->controllerName = 'PostController';
    }

    private function paginateSelect()
    {
        return [
            'posts.id',
            'posts.publish',
            'posts.image',
            'posts.order',
            'posts.album',
            'tb2.name',
            'tb2.canonical',
        ];
    }

    private function payload()
    {
        return ['follow', 'publish', 'image', 'album', 'post_catalogue_id'];
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
        $condition['keyword'] = addcslashes($request->input('keyword'), '\\%_');
        $condition['publish'] = $request->integer('publish');
        $condition['where'] = [
            ['tb2.language_id', '=', $languageId],
        ];
        $perpage = $request->integer('perpage');

        //Tìm danh mục con: $postCatalogueId --> truy vấn --> sử dụng lft rgt để tìm danh mục con bên trong
        // Lấy ra id các danh mục con. --> wherein tb3.post_catalogue_id IN (id danh mục con)

        // $subqueryWhere = (
        //     SELECT id FROM post_catalogues WHERE lft >= (SELECT left FROM post_catalogues WHERE id = $postCatalogueId)
        //      AND rgt <= (SELECT right FROM post_catalogues WHERE id = $postCatalogueId)
        // )

        $posts = $this->postRepository
            ->pagination(
                $this->paginateSelect(),
                $condition,
                $perpage,
                ['path' => 'post/index'],
                [
                    'posts.id',
                    'DESC'
                ],
                [
                    ['post_language as tb2', 'tb2.post_id', '=', 'posts.id'],
                    ['post_catalogue_post as tb3', 'posts.id', '=', 'tb3.post_id']
                ],
                ['post_catalogues'],
                $this->whereRaw($request, $languageId)
            );
        return $posts;
    }

    private function whereRaw($request, $languageId)
    {
        $rawCondition = [];
        if ($request->integer('post_catalogue_id') > 0) {
            $rawCondition['whereRaw'] = [
                [
                    'tb3.post_catalogue_id IN (
                        SELECT id
                        FROM post_catalogues
                        JOIN post_catalogue_language ON post_catalogues.id = post_catalogue_language.post_catalogue_id
                        WHERE lft >= (SELECT lft FROM post_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM post_catalogues as pc WHERE pc.id = ?)
                        AND post_catalogue_language.language_id = ' . $languageId . '
                    )',
                    [$request->integer('post_catalogue_id'), $request->integer('post_catalogue_id')]
                ]
            ];

        }
        return $rawCondition;
    }


    public function create(Request $request, $languageId)
    {
        DB::beginTransaction();
        try {
            $post = $this->createPost($request);
            if ($post->id > 0) {
                $this->updateLanguageForPost($post, $request, $languageId);
                $this->updateCatalogueForPost($post, $request, $languageId);
                $this->createRouter($post, $request, $this->controllerName, $languageId);
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

    private function catalogue($request)
    {
        if ($request->input('catalogue') != null) {
            return array_unique(array_merge($request->input('catalogue'), [$request->post_catalogue_id]));
        }

        return [$request->post_catalogue_id];
    }

    private function createPost($request)
    {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($payload);
        $payload['user_id'] = Auth::id();
        $post = $this->postRepository->create($payload);

        return $post;
    }

    private function uploadPost($post, $request)
    {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        return $this->postRepository->update($post->id, $payload);
    }

    private function updateLanguageForPost($post, $request, $languageId)
    {
        $payload = $request->only($this->payloadLanguage());
        $payload = $this->formatLanguagePayload($payload, $post->id, $languageId);
        DB::table('post_language') // Tên bảng pivot
            ->where('post_id', $post->id)
            ->where('language_id', $languageId)
            ->delete();
        return $this->postRepository->createPivot($post, $payload, 'languages');
    }

    private function updateCatalogueForPost($post, $request)
    {
        $post->post_catalogues()->sync($this->catalogue($request));
    }

    private function formatLanguagePayload($payload, $postId, $languageId)
    {
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] = $languageId;
        $payload['post_id'] = $postId;

        return $payload;
    }

    public function update($id, $request, $languageId)
    {
        DB::beginTransaction();
        try {
            $post = $this->postRepository->findById($id);
            if ($this->uploadPost($post, $request)) {
                $this->updateLanguageForPost($post, $request, $languageId);
                $this->updateCatalogueForPost($post, $request);
                $this->updateRouter(
                    $post,
                    $request,
                    $this->controllerName,
                    $languageId
                );
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

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->postRepository->forceDelete($id);

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
            $this->postRepository->update($post['modelId'], $payload);
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
            $flag = $this->postRepository->updateByWhereIn('id', $post['ids'], $payload);
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
            $this->postRepository->updateByWhereIn('id', $array, $payload);
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
