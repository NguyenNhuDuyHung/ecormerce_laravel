<?php

namespace App\Services;

use App\Services\Interfaces\UserCatalogueServiceInterface;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserCatalogueService
 * @package App\Services
 */
class UserCatalogueService implements UserCatalogueServiceInterface
{
    protected $userCatalogueRepository;
    public function __construct(UserCatalogueRepository $userCatalogueRepository)
    {
        $this->userCatalogueRepository = $userCatalogueRepository;
    }

    private function paginateSelect()
    {
        return ['id', 'name', 'description', 'publish'];
    }

    public function paginate($request)
    {
        $condition['keyword'] = addcslashes($request->input('keyword'), '\\%_');
        $condition['publish'] = $request->integer('publish');
        $perpage = $request->integer('perpage');
        $userCatalogues = $this->userCatalogueRepository
            ->pagination($this->paginateSelect(), $condition, [], $perpage, ['path' => 'user/catalogue/index'], ['users']);
        return $userCatalogues;
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $users = $this->userCatalogueRepository->create($payload);
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
            $user = $this->userCatalogueRepository->update($id, $payload);
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

    private function convertBirthdayDate($birthday = '')
    {
        $carbonDate = Carbon::createFromFormat('Y-m-d', $birthday);
        $birthday = $carbonDate->format('Y-m-d H:i:s');

        return $birthday;
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userCatalogueRepository->forceDelete($id);
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

    public function updateStatus($status = [])
    {
        DB::beginTransaction();
        try {
            $field = $status['field'];
            $payload = [$field => $status['value'] == 1 ? 2 : 1];
            $user = $this->userCatalogueRepository->update($status['modelId'], $payload);
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

    public function updateStatusAll($status = [])
    {
        DB::beginTransaction();
        try {
            $field = $status['field'];
            $payload = [$field => $status['value'] == 1 ? 2 : 1];
            $flag = $this->userCatalogueRepository->updateByWhereIn('id', $status['ids'], $payload);
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
