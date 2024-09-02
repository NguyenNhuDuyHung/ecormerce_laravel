<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\PostCatalogueServiceInterface as PostCatalogueService;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Http\Requests\StorePostCatalogueRequest;
use App\Http\Requests\UpdatePostCatalogueRequest;
use Illuminate\Http\Request;
use App\Classes\Nestedsetbie;
use App\Http\Requests\DeletePostCatalogueRequest;
use App\Models\Language;

class PostCatalogueController extends Controller
{
    protected $postCatalogueService;
    protected $postCatalogueRepository;
    protected $nestedSet;
    protected $language;

    public function __construct(PostCatalogueService $postCatalogueService, PostCatalogueRepository $postCatalogueRepository)
    {
        $this->postCatalogueService = $postCatalogueService;
        $this->postCatalogueRepository = $postCatalogueRepository;

        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
    }

    private function initialize()
    {
        $this->nestedSet = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreignkey' => 'post_catalogue_id',
            'language_id' => $this->language,
        ]);
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'post.catalogue.index');
        $postCatalogues = $this->postCatalogueService->paginate($request, $this->language);
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
            'model' => 'PostCatalogue',
        ];
        $config['seo'] = __('message.post');

        $template = 'backend.post.catalogue.index';
        return view("backend.dashboard.layout", compact('template', 'config', 'postCatalogues'));
    }

    public function create()
    {
        $this->authorize('modules', 'post.catalogue.create');

        $config = $this->configData();

        $config['seo'] = config('apps.postcatalogue');
        $config['method'] = 'create';

        $dropdown = $this->nestedSet->Dropdown();
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'dropdown'));
    }

    public function store(StorePostCatalogueRequest $request)
    {
        if ($this->postCatalogueService->create($request, $this->language)) {
            return redirect()->route('post.catalogue.index')->with("success", "Đã thêm nhóm người dùng");
        }
        return redirect()->route("post.catalogue.create")->with("error", "Đã xảy ra lỗi khi thêm nhóm người dùng");
    }

    public function edit($id)
    {
        $this->authorize('modules', 'post.catalogue.update');

        $config = $this->configData();
        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        $config['seo'] = config('apps.postcatalogue');
        $config['method'] = 'edit';
        $dropdown = $this->nestedSet->Dropdown();
        $album = $postCatalogue->album;
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'postCatalogue', 'dropdown', 'album'));
    }

    public function update($id, UpdatePostCatalogueRequest $request)
    {
        if ($this->postCatalogueService->update($id, $request, $this->language)) {
            return redirect()->route('post.catalogue.index')->with('success', 'Cập nhật thông tin thành công.');
        }
        return redirect()->route('post.catalogue.edit', $id)->with('error', 'Đã xảy ra lỗi khi cập nhật. Vui lòng thử lại sau.');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'post.catalogue.destroy');

        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        $config['seo'] = config('apps.postcatalogue');
        $template = 'backend.post.catalogue.delete';
        return view("backend.dashboard.layout", compact('template', 'postCatalogue', 'config'));
    }

    public function destroy($id, DeletePostCatalogueRequest $request)
    {
        if ($this->postCatalogueService->destroy($id, $this->language)) {
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
                'backend/library/seo.js',
            ]
        ];
    }
}
