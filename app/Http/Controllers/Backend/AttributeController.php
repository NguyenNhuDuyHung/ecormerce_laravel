<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\AttributeServiceInterface as AttributeService;
use App\Repositories\Interfaces\AttributeRepositoryInterface as AttributeRepository;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Http\Requests\DeleteAttributeRequest;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class AttributeController extends Controller
{
    protected $attributeService;
    protected $attributeRepository;
    protected $languageRepository;
    protected $language;

    public function __construct(
        AttributeService $attributeService,
        AttributeRepository $attributeRepository,
    ) {
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale(); // vn en cn
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });

        $this->attributeService = $attributeService;
        $this->attributeRepository = $attributeRepository;
        $this->initialize();

    }

    private function initialize()
    {
        $this->nestedset = new Nestedsetbie([
            'table' => 'attribute_catalogues',
            'foreignkey' => 'attribute_catalogue_id',
            'language_id' => $this->language,
        ]);
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'attribute.index');
        $attributes = $this->attributeService->paginate($request, $this->language);
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
            'model' => 'Attribute'
        ];
        $config['seo'] = __('message.attribute');
        $template = 'backend.attribute.attribute.index';
        $dropdown = $this->nestedset->Dropdown();
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown',
            'attributes'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'attribute.create');
        $config = $this->configData();
        $config['seo'] = __('message.attribute');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.attribute.attribute.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'dropdown',
            'config',
        ));
    }

    public function store(StoreAttributeRequest $request)
    {
        if ($this->attributeService->create($request, $this->language)) {
            return redirect()->route('attribute.index')->with('success', 'Thêm mới bản ghi thành công');
        }
        return redirect()->route('attribute.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id)
    {
        $this->authorize('modules', 'attribute.update');
        $attribute = $this->attributeRepository->getAttributeById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = __('message.attribute');
        $config['method'] = 'edit';
        $dropdown = $this->nestedset->Dropdown();
        $album = json_decode($attribute->album);
        $template = 'backend.attribute.attribute.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown',
            'attribute',
            'album',
        ));
    }

    public function update($id, UpdateAttributeRequest $request)
    {
        if ($this->attributeService->update($id, $request, $this->language)) {
            return redirect()->route('attribute.index')->with('success', 'Cập nhật bản ghi thành công');
        }
        return redirect()->route('attribute.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'attribute.destroy');
        $config['seo'] = __('message.attribute');
        $attribute = $this->attributeRepository->getAttributeById($id, $this->language);
        $template = 'backend.attribute.attribute.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'attribute',
            'config',
        ));
    }

    public function destroy($id)
    {
        if ($this->attributeService->destroy($id, $this->language)) {
            return redirect()->route('attribute.index')->with('success', 'Xóa bản ghi thành công');
        }
        return redirect()->route('attribute.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại');
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
