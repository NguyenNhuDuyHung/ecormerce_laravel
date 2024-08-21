<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\PostServiceInterface as PostService;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use App\Classes\Nestedsetbie;

class PostController extends Controller
{
    protected $postService;
    protected $postRepository;
    protected $nestedSet;
    protected $language;
    protected $languageRepository;

    public function __construct(PostService $postService, PostRepository $postRepository, Nestedsetbie $nestedSet, LanguageRepository $languageRepository)
    {
        $this->postService = $postService;
        $this->postRepository = $postRepository;
        $this->language = $this->currentLanguage();
        $this->nestedSet = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreignkey' => 'post_catalogue_id',
            'language_id' => $this->language,
        ]);
    }

    public function index(Request $request)
    {
        $posts = $this->postService->paginate($request);

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
            'model' => 'Post',
        ];
        $config['seo'] = config('apps.post');
        $dropdown = $this->nestedSet->Dropdown();
        $template = 'backend.post.post.index';
        return view("backend.dashboard.layout", compact('template', 'config', 'posts', 'dropdown'));
    }

    public function create()
    {
        $config = $this->configData();
        $config['seo'] = config('apps.post');
        $config['method'] = 'create';

        $dropdown = $this->nestedSet->Dropdown();
        $template = 'backend.post.post.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'dropdown'));
    }

    public function store(StorePostRequest $request)
    {
        if ($this->postService->create($request)) {
            return redirect()->route('post.index')->with("success", "Đã thêm nhóm người dùng");
        }
        return redirect()->route("post.create")->with("error", "Đã xảy ra lỗi khi thêm nhóm người dùng");
    }

    public function edit($id)
    {
        $config = $this->configData();
        $post = $this->postRepository->getPostById($id, $this->language);
        $config['seo'] = config('apps.post');
        $config['method'] = 'edit';
        $dropdown = $this->nestedSet->Dropdown();
        $album = $post->album;
        $template = 'backend.post.post.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'post', 'dropdown', 'album'));
    }

    public function update($id, UpdatePostRequest $request)
    {
        if ($this->postService->update($id, $request)) {
            return redirect()->route('post.index')->with('success', 'Cập nhật thông tin thành công.');
        }
        return redirect()->route('post.index')->with('error', 'Đã xảy ra lỗi khi cập nhật. Vui lòng thử lại sau.');
    }

    public function delete($id)
    {
        $post = $this->postRepository->getPostById($id, $this->language);
        $config['seo'] = config('apps.post');
        $template = 'backend.post.post.delete';
        return view("backend.dashboard.layout", compact('template', 'post', 'config'));
    }

    public function destroy($id)
    {
        if ($this->postService->destroy($id)) {
            return redirect()->route('post.index')->with('success', 'Đã xoá nhóm người dùng');
        }
        return redirect()->route('post.index')->with('error', 'Đã xảy ra lỗi khi xoá nhóm người dùng');
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
