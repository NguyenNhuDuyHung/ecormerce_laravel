<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\PostCatalogueServiceInterface as PostCatalogueService;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Http\Requests\StorePostCatalogueRequest;
use App\Http\Requests\UpdatePostCatalogueRequest;
use Illuminate\Http\Request;

class PostCatalogueController extends Controller
{
    protected $postCatalogueService;
    protected $postCatalogueRepository;

    public function __construct(PostCatalogueService $postCatalogueService, PostCatalogueRepository $postCatalogueRepository)
    {
        $this->postCatalogueService = $postCatalogueService;
        $this->postCatalogueRepository = $postCatalogueRepository;
    }

    public function index(Request $request)
    {
        $postCatalogues = $this->postCatalogueService->paginate($request);

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
        $config['seo'] = config('apps.postcatalogue');

        $template = 'backend.post.catalogue.index';
        return view("backend.dashboard.layout", compact('template', 'config', 'postCatalogues'));
    }

    public function create()
    {
        $config = $this->configData();

        $config['seo'] = config('apps.postcatalogue');
        $config['method'] = 'create';

        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StorePostCatalogueRequest $request)
    {
        if ($this->postCatalogueService->create($request)) {
            return redirect()->route('post.catalogue.index')->with("success", "Đã thêm nhóm người dùng");
        }
        return redirect()->route("post.catalogue.create")->with("error", "Đã xảy ra lỗi khi thêm nhóm người dùng");
    }

    public function edit($id)
    {
        $config = $this->configData();
        $postCatalogue = $this->postCatalogueRepository->findById($id);
        $config['seo'] = config('apps.postcatalogue');
        $config['method'] = 'edit';
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'postCatalogue'));
    }

    public function update($id, UpdatePostCatalogueRequest $request)
    {
        if ($this->postCatalogueService->update($id, $request)) {
            return redirect()->route('post.catalogue.index')->with('success', 'Cập nhật thông tin thành công.');
        }
        return redirect()->route('post.catalogue.edit', $id)->with('error', 'Đã xảy ra lỗi khi cập nhật. Vui lòng thử lại sau.');
    }

    public function delete($id)
    {
        $config['seo'] = config('apps.postcatalogue');
        $postCatalogue = $this->postCatalogueRepository->findById($id);
        $template = 'backend.post.catalogue.delete';
        return view("backend.dashboard.layout", compact('template', 'postCatalogue', 'config'));
    }

    public function destroy($id)
    {
        if ($this->postCatalogueService->destroy($id)) {
            return redirect()->route('post.catalogue.index')->with('success', 'Đã xoá nhóm người dùng');
        }
        return redirect()->route('post.catalogue.index')->with('error', 'Đã xảy ra lỗi khi xoá nhóm người dùng');
    }

    private function configData()
    {
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/select2.js',
                'backend/plugins/ckeditor/ckeditor.js',
            ]
        ];
    }
}
