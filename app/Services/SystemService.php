<?php

namespace App\Services;

use App\Services\Interfaces\SystemServiceInterface;
use App\Repositories\Interfaces\SystemRepositoryInterface as SystemRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class SystemService
 * @package App\Services
 */
class SystemService implements SystemServiceInterface
{
    protected $systemRepository;
    public function __construct(SystemRepository $systemRepository)
    {
        $this->systemRepository = $systemRepository;
    }

    public function save(Request $request, $languageId)
    {
        DB::beginTransaction();
        try {
            $config = $request->input("config");
            if (count($config)) {
                foreach ($config as $key => $value) {
                    // Tạo payload cho từng phần tử
                    $payload = [
                        'keyword' => $key,
                        'content' => $value,
                        'language_id' => $languageId,
                        'user_id' => Auth::id()
                    ];
                    $condition = ['keyword' => $key, 'language_id' => $languageId];

                    // Gọi updateOrInsert cho mỗi phần tử
                    $this->systemRepository->updateOrInsert($payload, $condition);
                }
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
}
