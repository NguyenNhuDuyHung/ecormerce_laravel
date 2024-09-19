<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;
use App\Services\Interfaces\MenuCatalogueServiceInterface as MenuCatalogueService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMenuCatalogueRequest;

class MenuController extends Controller
{
    protected $menuCatalogueRepository;
    protected $menuCatalogueService;
    public function __construct(MenuCatalogueRepository $menuCatalogueRepository, MenuCatalogueService $menuCatalogueService)
    {
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->menuCatalogueService = $menuCatalogueService;
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

}
