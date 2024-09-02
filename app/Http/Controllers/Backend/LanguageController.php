<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\LanguageServiceInterface as LanguageService;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use Illuminate\Http\Request;
use App\Http\Requests\TranslateRequest;

class LanguageController extends Controller
{
    protected $languageService;
    protected $languageRepository;

    public function __construct(LanguageService $languageService, LanguageRepository $languageRepository)
    {
        $this->languageService = $languageService;
        $this->languageRepository = $languageRepository;
    }
    public function index(Request $request)
    {
        $this->authorize('modules', 'language.index');
        $languages = $this->languageService->paginate($request);

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
            'model' => 'Language',
        ];
        $config['seo'] = config('apps.language');

        $template = 'backend.language.index';
        return view("backend.dashboard.layout", compact('template', 'config', 'languages'));
    }

    public function create()
    {
        $this->authorize('modules', 'language.create');
        $config = $this->configData();

        $config['seo'] = config('apps.language');
        $config['method'] = 'create';

        $template = 'backend.language.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StoreLanguageRequest $request)
    {
        if ($this->languageService->create($request)) {
            return redirect()->route('language.index')->with("success", "Đã thêm nhóm người dùng");
        }
        return redirect()->route("language.create")->with("error", "Đã xảy ra lỗi khi thêm nhóm người dùng");
    }

    public function edit($id)
    {
        $this->authorize('modules', 'language.update');
        $config = $this->configData();
        $language = $this->languageRepository->findById($id);
        $config['seo'] = config('apps.language');
        $config['method'] = 'edit';
        $template = 'backend.language.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'language'));
    }

    public function update($id, UpdateLanguageRequest $request)
    {
        if ($this->languageService->update($id, $request)) {
            return redirect()->route('language.index')->with('success', 'Cập nhật thông tin thành công.');
        }
        return redirect()->route('language.edit', $id)->with('error', 'Đã xảy ra lỗi khi cập nhật. Vui lòng thử lại sau.');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'language.delete');

        $config['seo'] = config('apps.language');
        $language = $this->languageRepository->findById($id);
        $template = 'backend.language.delete';
        return view("backend.dashboard.layout", compact('template', 'language', 'config'));
    }

    public function destroy($id)
    {
        if ($this->languageService->destroy($id)) {
            return redirect()->route('language.index')->with('success', 'Đã xoá nhóm người dùng');
        }
        return redirect()->route('language.index')->with('error', 'Đã xảy ra lỗi khi xoá nhóm người dùng');
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

    public function switchBackendLanguage($id)
    {
        $language = $this->languageRepository->findById($id);
        if ($this->languageService->switch($id)) {
            session(['app_locale' => $language->canonical]);
            \App::setLocale($language->canonical);
        }
        return back();
    }

    public function translate($id = 0, $languageId = 0, $model = '')
    {
        $repositoryInstance = $this->repositoryInstance($model);
        $languageInstance = $this->repositoryInstance('Language');
        $currentLanguage = $languageInstance->findByCondition([
            ['canonical', '=', session('app_locale')]
        ]);
        $methodName = 'get' . ucfirst($model) . 'ById';
        $object = $repositoryInstance->{$methodName}($id, $currentLanguage->id);
        $objectTranslate = $repositoryInstance->{$methodName}($id, $languageId);
        $this->authorize('modules', 'language.translate');
        $config = [
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

        $option = [
            'id' => $id,
            'languageId' => $languageId,
            'model' => $model,
        ];

        $config['seo'] = config('apps.language');
        $template = 'backend.language.translate';
        return view(
            'backend.dashboard.layout',
            compact('template', 'config', 'object', 'objectTranslate', 'option')
        );
    }

    public function storeTranslate(TranslateRequest $request)
    {
        $option = $request->input('option');
        if ($this->languageService->saveTranslate($option, $request)) {
            return redirect()->back()->with('success', 'Đã cập nhật ngôn ngữ');
        }
        return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật. Vui lòng thử lại sau.');
    }

    private function repositoryInstance($model)
    {
        $repositoryNamespace = 'App\Repositories\\' . ucfirst($model) . 'Repository';
        if (class_exists($repositoryNamespace)) {
            $repositoryInstance = app($repositoryNamespace);
        }

        return $repositoryInstance;
    }
}
