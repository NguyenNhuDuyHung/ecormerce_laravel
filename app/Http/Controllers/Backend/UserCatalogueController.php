<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\UserCatalogueServiceInterface as UserCatalogueService;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;

class UserCatalogueController extends Controller
{
    protected $userCatalogueService;
    protected $userRepository;

    public function __construct(UserCatalogueService $userCatalogueService, UserRepository $userRepository)
    {
        $this->userCatalogueService = $userCatalogueService;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
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
            ]
        ];
        $config['seo'] = config('apps.user');

        $template = 'backend.user.catalogue.index';
        return view("backend.dashboard.layout", compact('template', 'config', 'userCatalogues'));
    }

    public function create()
    {
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

        $config['seo'] = config('apps.user');
        $config['method'] = 'create';

        $template = 'backend.user.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'provinces'));
    }

    public function store(StoreUserRequest $request)
    {
        if ($this->userCatalogueService->create($request)) {
            return redirect()->route('user.index')->with("success", "Đã thêm người dùng");
        }
        return redirect()->route("user.create")->with("error", "Đã xảy ra lỗi khi thêm người dùng");
    }

    public function edit($id)
    {
        $user = $this->userRepository->findById($id);
        $config = [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/select2.js',
                'backend/library/location.js',
            ]
        ];
        $config['seo'] = config('apps.user');
        $config['method'] = 'edit';
        $template = 'backend.user.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'provinces', 'user'));
    }

    public function update($id, UpdateUserRequest $request)
    {
        if ($this->userCatalogueService->update($id, $request)) {
            return redirect()->route('user.index')->with('success', 'Cập nhật thông tin thành công.');
        }
        return redirect()->route('user.edit', $id)->with('error', 'Đã xảy ra lỗi khi cập nhật. Vui lòng thử lại sau.');
    }

    public function delete($id)
    {
        $config['seo'] = config('apps.user');
        $user = $this->userRepository->findById($id);
        $template = 'backend.user.catalogue.delete';
        return view("backend.dashboard.layout", compact('template', 'user', 'config'));
    }

    public function destroy($id)
    {
        if ($this->userCatalogueService->destroy($id)) {
            return redirect()->route('user.index')->with('success', 'Đã xoá người dùng');
        }
        return redirect()->route('user.index')->with('error', 'Đã xảy ra lỗi khi xoá người dùng');
    }
}
