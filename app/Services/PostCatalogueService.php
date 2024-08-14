<?php

namespace App\Services;

use App\Services\Interfaces\PostCatalogueServiceInterface;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class PostCatalogueService
 * @package App\Services
 */
class PostCatalogueService implements PostCatalogueServiceInterface
{
    protected $postCatalogueRepository;
    public function __construct(PostCatalogueRepository $postCatalogueRepository)
    {
        $this->postCatalogueRepository = $postCatalogueRepository;
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
        $postCatalogues = $this->postCatalogueRepository
            ->pagination($this->paginateSelect(), $condition, [], $perpage, ['path' => 'post/catalogue/index']);
        return $postCatalogues;
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $payload['user_id'] = Auth::id();
            $this->postCatalogueRepository->create($payload);
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
            $this->postCatalogueRepository->update($id, $payload);
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
            $this->postCatalogueRepository->forceDelete($id);
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

    public function updateStatus($post = []){
        DB::beginTransaction();
        try{
            $payload[$post['field']] = (($post['value'] == 1)?2:1);
            $this->postCatalogueRepository->update($post['modelId'], $payload);
            // $this->changeUserStatus($post, $payload[$post['field']]);

            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    public function updateStatusAll($post){
        DB::beginTransaction();
        try{
            $payload[$post['field']] = $post['value'];
            $flag = $this->postCatalogueRepository->updateByWhereIn('id', $post['ids'], $payload);
            // $this->changeUserStatus($post, $post['value']);

            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
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
