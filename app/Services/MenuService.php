<?php

namespace App\Services;

use App\Services\Interfaces\MenuServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Classes\Nestedsetbie;

/**
 * Class MenuService
 * @package App\Services
 */
class MenuService extends BaseService implements MenuServiceInterface
{
    protected $menuRepository;
    protected $nestedSet;

    public function __construct(
        MenuRepository $menuRepository,
    ) {
        $this->menuRepository = $menuRepository;
        $this->controllerName = 'MenuController';
    }

    public function paginate($request)
    {
        return [];
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only('menu', 'menu_catalogue_id', 'type');

            if (count($payload['menu']['name'])) {
                foreach ($payload['menu']['name'] as $key => $value) {
                    $menuArray = [
                        'menu_catalogue_id' => $payload['menu_catalogue_id'],
                        'type' => $payload['type'],
                        'order' => $payload['menu']['order'][$key],
                        'user_id' => Auth::id(),
                    ];

                    $menu = $this->menuRepository->create($menuArray);

                    if ($menu->id > 0) {
                        DB::table('menu_language') // Tên bảng pivot
                            ->where('menu_id', $menu->id)
                            ->where('language_id', $languageId)
                            ->delete();
                        $payloadLanguage = [
                            'language_id' => $languageId,
                            'name' => $value,
                            'canonical' => $payload['menu']['canonical'][$key],
                        ];
                        $this->menuRepository->createPivot($menu, $payloadLanguage, 'languages');
                    }
                }

                $this->nestedSet = new Nestedsetbie([
                    'table' => 'menus',
                    'foreignkey' => 'menu_id',
                    'isMenu' => TRUE,
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

    public function update($id, $request, $languageId)
    {
        DB::beginTransaction();
        try {

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

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error($e->getMessage());
            // echo $e->getMessage();die();
            return false;
        }
    }
}
