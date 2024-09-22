<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;
use App\Services\Interfaces\MenuCatalogueServiceInterface as MenuCatalogueService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMenuCatalogueRequest;
use App\Services\Interfaces\MenuServiceInterface as MenuService;
use App\Models\Language;

class MenuController extends Controller
{
    protected $menuCatalogueRepository;
    protected $menuCatalogueService;
    protected $menuService;
    protected $language;
    public function __construct(MenuCatalogueRepository $menuCatalogueRepository, MenuCatalogueService $menuCatalogueService, MenuService $menuService)
    {
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->menuCatalogueService = $menuCatalogueService;
        $this->menuService = $menuService;
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale(); // vn en cn
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function createCatalogue(StoreMenuCatalogueRequest $request)
    {
        $menuCatalogue = $this->menuCatalogueService->create($request);
        if ($menuCatalogue !== false) {
            return response()->json([
                'messages' => "Tạo thành công!",
                'code' => 0,
                'data' => $menuCatalogue
            ]);
        }
        return response()->json([
            'messages' => 'Đã xảy ra lỗi, vui lòng thử lại sau!',
            'code' => '1'
        ]);
    }

    public function drag(Request $request)
    {
        $json = json_decode($request->string('json'), TRUE);
        $menuCatalogueId = $request->integer('menu_catalogue_id');

        $flag = $this->menuService->dragUpdate($json, $menuCatalogueId);
    }

}
