<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\PermissionServiceInterface as PermissionService;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $permissionService;
    protected $permissionRepository;

    public function __construct(PermissionService $permissionService, PermissionRepository $permissionRepository)
    {
        $this->permissionService = $permissionService;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'permission.index');

        $permissions = $this->permissionService->paginate($request);

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
            'model' => 'Permission',
        ];
        $config['seo'] = __('message.permission');

        $template = 'backend.permission.index';
        return view("backend.dashboard.layout", compact('template', 'config', 'permissions'));
    }

    public function create()
    {
        $this->authorize('modules', 'permission.create');

        $config = $this->configData();

        $config['seo'] = __('message.permission');
        $config['method'] = 'create';

        $template = 'backend.permission.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StorePermissionRequest $request)
    {
        if ($this->permissionService->create($request)) {
            return redirect()->route('permission.index')->with("success", "Đã thêm nhóm người dùng");
        }
        return redirect()->route("permission.create")->with("error", "Đã xảy ra lỗi khi thêm nhóm người dùng");
    }

    public function edit($id)
    {
        $this->authorize('modules', 'permission.update');

        $config = $this->configData();
        $permission = $this->permissionRepository->findById($id);
        $config['seo'] = __('message.permission');
        $config['method'] = 'edit';
        $template = 'backend.permission.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'permission'));
    }

    public function update($id, UpdatePermissionRequest $request)
    {
        if ($this->permissionService->update($id, $request)) {
            return redirect()->route('permission.index')->with('success', 'Cập nhật thông tin thành công.');
        }
        return redirect()->route('permission.edit', $id)->with('error', 'Đã xảy ra lỗi khi cập nhật. Vui lòng thử lại sau.');
    }

    public function delete($id)
    {
        $config['seo'] = __('message.permission');
        $permission = $this->permissionRepository->findById($id);
        $template = 'backend.permission.delete';
        return view("backend.dashboard.layout", compact('template', 'permission', 'config'));
    }

    public function destroy($id)
    {
        if ($this->permissionService->destroy($id)) {
            return redirect()->route('permission.index')->with('success', 'Đã xoá nhóm người dùng');
        }
        return redirect()->route('permission.index')->with('error', 'Đã xảy ra lỗi khi xoá nhóm người dùng');
    }

    private function configData()
    {
        return [
            'js' => [
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
            ]
        ];
    }
}
