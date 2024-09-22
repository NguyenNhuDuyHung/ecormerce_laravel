<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\MenuServiceInterface as MenuService;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;
use App\Services\Interfaces\MenuCatalogueServiceInterface as MenuCatalogueService;
use App\Http\Requests\StoreMenuChildrenRequest;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use Illuminate\Http\Request;
use App\Models\Language;

class MenuController extends Controller
{
    protected $menuService;
    protected $menuRepository;
    protected $menuCatalogueRepository;
    protected $language;
    protected $menuCatalogueService;

    public function __construct(
        MenuService $menuService,
        MenuRepository $menuRepository,
        MenuCatalogueRepository $menuCatalogueRepository,
        MenuCatalogueService $menuCatalogueService
    ) {
        $this->menuService = $menuService;
        $this->menuRepository = $menuRepository;
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->menuCatalogueService = $menuCatalogueService;
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale(); // vn en cn
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'menu.index');
        $menuCatalogues = $this->menuCatalogueService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'backend/library/switchery.js',
                'backend/library/changeStatus.js',
                'backend/library/selectAll.js',
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ],
            'model' => 'MenuCatalogue',
        ];
        $config['seo'] = __('message.menu');

        $template = 'backend.menu.menu.index';
        return view("backend.dashboard.layout", compact('template', 'config', 'menuCatalogues'));
    }

    public function create()
    {
        $this->authorize('modules', 'menu.create');
        $menuCatalogues = $this->menuCatalogueRepository->all();
        $config = $this->configData();

        $config['seo'] = __('message.menu');
        $config['method'] = 'create';

        $template = 'backend.menu.menu.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'menuCatalogues'));
    }

    public function store(StoreMenuRequest $request)
    {
        if ($this->menuService->create($request, $this->language)) {
            return redirect()->route('menu.index')->with("success", "Đã thêm thành công");
        }
        return redirect()->route("menu.create")->with("error", "Đã xảy ra lỗi khi, hãy thử lại sau!");
    }

    public function edit($id)
    {
        $this->authorize('modules', 'menu.update');
        $language = $this->language;
        $menus = $this->menuRepository->findByCondition([
            ['menu_catalogue_id', '=', $id],
        ], TRUE, [
            'languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }
        ], ['order', 'DESC']);

        $config = $this->configData();
        $config['seo'] = __('message.menu');
        $config['method'] = 'show';
        $template = 'backend.menu.menu.show';
        return view('backend.dashboard.layout', compact('template', 'config', 'menus', 'id'));
    }

    public function children($id)
    {
        $this->authorize('modules', 'menu.create');
        $language = $this->language;

        $menu = $this->menuRepository->findById($id, ['*'], [
            'languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }
        ]);

        $menuChildren = $this->menuService->getAndConvertMenu($menu, $language);

        $config = $this->configData();
        $config['seo'] = __('message.menu');
        $config['method'] = 'children';

        $template = 'backend.menu.menu.children';
        return view('backend.dashboard.layout', compact('template', 'config', 'menu', 'menuChildren'));
    }

    public function saveChildren($id, StoreMenuChildrenRequest $request)
    {
        $menu = $this->menuRepository->findById($id);
        if ($this->menuService->saveChildren($request, $this->language, $menu)) {
            return redirect()->route('menu.edit', ['id' => $menu->menu_catalogue_id])->with("success", "Đã thêm thành công");
        }
        return redirect()->route("menu.edit", ['id' => $menu->menu_catalogue_id])->with("error", "Đã xảy ra lỗi khi, hãy thử lại sau!");
    }

    public function update($id, UpdateMenuRequest $request)
    {
        if ($this->menuService->update($id, $request)) {
            return redirect()->route('menu.index')->with('success', 'Cập nhật thông tin thành công.');
        }
        return redirect()->route('menu.edit', $id)->with('error', 'Đã xảy ra lỗi khi cập nhật. Vui lòng thử lại sau.');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'menu.destroy');

        $config['seo'] = __('message.menu');
        $menu = $this->menuRepository->findById($id);
        $template = 'backend.menu.menu.delete';
        return view("backend.dashboard.layout", compact('template', 'menu', 'config'));
    }

    public function destroy($id)
    {
        if ($this->menuService->destroy($id)) {
            return redirect()->route('menu.index')->with('success', 'Đã xoá người dùng');
        }
        return redirect()->route('menu.index')->with('error', 'Đã xảy ra lỗi khi xoá người dùng');
    }

    private function configData()
    {
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/select2.js',
                'backend/library/menu.js',
                'backend/js/plugins/nestable/jquery.nestable.js',
            ]
        ];
    }
}
