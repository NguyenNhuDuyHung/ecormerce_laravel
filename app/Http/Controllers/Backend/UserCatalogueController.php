<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\UserCatalogueServiceInterface as UserCatalogueService;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
use App\Services\Interfaces\PermissionServiceInterface as PermissionService;

use App\Http\Requests\StoreUserCatalogueRequest;
use App\Http\Requests\UpdateUserCatalogueRequest;
use Illuminate\Http\Request;

class UserCatalogueController extends Controller
{
    protected $userCatalogueService;
    protected $userCatalogueRepository;
    protected $permissionService;
    protected $permissionRepository;

    public function __construct(
        UserCatalogueService $userCatalogueService,
        UserCatalogueRepository $userCatalogueRepository,
        PermissionService $permissionService,
        PermissionRepository $permissionRepository
    ) {
        $this->userCatalogueService = $userCatalogueService;
        $this->userCatalogueRepository = $userCatalogueRepository;
        $this->permissionService = $permissionService;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'user.catalogue.index');

        $userCatalogues = $this->userCatalogueService->paginate($request);

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
            'model' => 'UserCatalogue',
        ];
        $config['seo'] = config('apps.usercatalogue');

        $template = 'backend.user.catalogue.index';
        return view("backend.dashboard.layout", compact('template', 'config', 'userCatalogues'));
    }

    public function create()
    {
        $this->authorize('modules', 'user.catalogue.create');

        $config = [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/select2.js',
                'backend/library/location.js',
                'backend/library/finder.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
            ]
        ];

        $config['seo'] = config('apps.usercatalogue');
        $config['method'] = 'create';

        $template = 'backend.user.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StoreUserCatalogueRequest $request)
    {
        if ($this->userCatalogueService->create($request)) {
            return redirect()->route('user.catalogue.index')->with("success", "Đã thêm nhóm người dùng");
        }
        return redirect()->route("user.catalogue.create")->with("error", "Đã xảy ra lỗi khi thêm nhóm người dùng");
    }

    public function edit($id)
    {
        $this->authorize('modules', 'user.catalogue.update');

        $userCatalogue = $this->userCatalogueRepository->findById($id);
        $config['seo'] = config('apps.usercatalogue');
        $config['method'] = 'edit';
        $template = 'backend.user.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'userCatalogue'));
    }

    public function update($id, UpdateUserCatalogueRequest $request)
    {
        if ($this->userCatalogueService->update($id, $request)) {
            return redirect()->route('user.catalogue.index')->with('success', 'Cập nhật thông tin thành công.');
        }
        return redirect()->route('user.catalogue.edit', $id)->with('error', 'Đã xảy ra lỗi khi cập nhật. Vui lòng thử lại sau.');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'user.catalogue.destroy');

        $config['seo'] = config('apps.usercatalogue');
        $userCatalogue = $this->userCatalogueRepository->findById($id);
        $template = 'backend.user.catalogue.delete';
        return view("backend.dashboard.layout", compact('template', 'userCatalogue', 'config'));
    }

    public function destroy($id)
    {
        if ($this->userCatalogueService->destroy($id)) {
            return redirect()->route('user.catalogue.index')->with('success', 'Đã xoá nhóm người dùng');
        }
        return redirect()->route('user.catalogue.index')->with('error', 'Đã xảy ra lỗi khi xoá nhóm người dùng');
    }

    public function permission()
    {
        $this->authorize('modules', 'user.catalogue.permission');

        $config['seo'] = __('message.userCatalogue');
        $permissions = $this->permissionRepository->all();
        $userCatalogues = $this->userCatalogueRepository->all(['permissions']);
        $template = 'backend.user.catalogue.permission';
        return view(
            'backend.dashboard.layout',
            compact('template', 'userCatalogues', 'permissions', 'config')
        );
    }

    public function updatePermission(Request $request)
    {
        $this->authorize('modules', 'user.catalogue.permission');
        if($this->userCatalogueService->setPermission($request)) {
            return redirect()->route('user.catalogue.index')->with('success', 'Đã cập nhật quyền');
        }
        return redirect()->route('user.catalogue.permission')->with('error', 'Đã xảy ra lỗi khi cập nhật. Vui lòng thử lại sau.');
    }
}
