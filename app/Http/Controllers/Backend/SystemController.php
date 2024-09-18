<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\System;
use App\Services\Interfaces\SystemServiceInterface as SystemService;
use App\Repositories\Interfaces\SystemRepositoryInterface as SystemRepository;
use App\Models\Language;

class SystemController extends Controller
{
    protected $systemLibrary;
    protected $systemService;
    protected $systemRepository;
    protected $language;

    public function __construct(System $systemLibrary, SystemService $systemService, SystemRepository $systemRepository)
    {
        $this->systemLibrary = $systemLibrary;
        $this->systemService = $systemService;
        $this->systemRepository = $systemRepository;
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale(); // vn en cn
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function index()
    {
        $systemConfig = $this->systemLibrary->config();

        $systems = convert_array(
            $this->systemRepository->
                findByCondition([['language_id', '=', $this->language]], TRUE),
            'keyword',
            'content'
        );
        $config = $this->config();
        $config['seo'] = __('message.system');
        $template = 'backend.system.index';
        return view("backend.dashboard.layout", compact(
            'template',
            'config',
            'systemConfig',
            'systems'
        ));
    }

    public function store(Request $request)
    {
        if ($this->systemService->save($request, $this->language)) {
            return redirect()->route('system.index')->with('success', 'Thành công');
        }
        return redirect()->route('system.index')->with('error', 'Đã xảy ra lỗi. Hãy thử lại');
    }

    private function config()
    {
        return [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/library/finder.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
            ]
        ];
    }
}