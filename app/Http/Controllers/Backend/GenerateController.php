<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\GenerateServiceInterface as GenerateService;
use App\Repositories\Interfaces\GenerateRepositoryInterface as GenerateRepository;
use App\Http\Requests\StoreGenerateRequest;
use App\Http\Requests\UpdateGenerateRequest;
use Illuminate\Http\Request;
use App\Http\Requests\TranslateRequest;


class GenerateController extends Controller
{
    protected $generateService;
    protected $generateRepository;

    public function __construct(GenerateService $generateService, GenerateRepository $generateRepository)
    {
        $this->generateService = $generateService;
        $this->generateRepository = $generateRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'generate.index');
        $generates = $this->generateService->paginate($request);

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
            'model' => 'Generate',
        ];
        $config['seo'] = __('message.generate');
        $template = 'backend.generate.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'generates'));
    }

    public function create()
    {
        $this->authorize('modules', 'generate.create');
        $config = $this->configData();

        $config['seo'] = __('message.generate');
        $config['method'] = 'create';

        $template = 'backend.generate.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StoreGenerateRequest $request)
    {
        if ($this->generateService->create($request)) {
            return redirect()->route('generate.index')->with('success', 'Đã thêm nhóm người dùng');
        }
        return redirect()->route('generate.create')->with('error', 'Đã xảy ra lỗi khi thêm nhóm người dùng');
    }

    public function edit($id)
    {
        $this->authorize('modules', 'generate.update');
        $config = $this->configData();
        $generate = $this->generateRepository->findById($id);
        $config['seo'] = __('message.generate');
        $config['method'] = 'edit';
        $template = 'backend.generate.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'generate'));
    }

    public function update($id, UpdateGenerateRequest $request)
    {
        if ($this->generateService->update($id, $request)) {
            return redirect()->route('generate.index')->with('success', 'Cập nhật thông tin thành công.');
        }
        return redirect()->route('generate.edit', $id)->with('error', 'Đã xảy ra lỗi khi cập nhật. Vui lòng thử lại sau.');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'generate.delete');

        $config['seo'] = __('message.generate');
        $generate = $this->generateRepository->findById($id);
        $template = 'backend.generate.delete';
        return view('backend.dashboard.layout', compact('template', 'generate', 'config'));
    }

    public function destroy($id)
    {
        if ($this->generateService->destroy($id)) {
            return redirect()->route('generate.index')->with('success', 'Đã xoá nhóm người dùng');
        }
        return redirect()->route('generate.index')->with('error', 'Đã xảy ra lỗi khi xoá nhóm người dùng');
    }

    private function configData()
    {
        return [
            'js' => [
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',

                'backend/library/select2.js',
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }
}