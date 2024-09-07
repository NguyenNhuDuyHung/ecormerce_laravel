<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\AttributeCatalogueServiceInterface  as AttributeCatalogueService;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface  as AttributeCatalogueRepository;
use App\Http\Requests\StoreAttributeCatalogueRequest;
use App\Http\Requests\UpdateAttributeCatalogueRequest;
use App\Http\Requests\DeleteAttributeCatalogueRequest;
use App\Classes\Nestedsetbie;
use Auth;
use App\Models\Language;
use Illuminate\Support\Facades\App;
class AttributeCatalogueController extends Controller
{

    protected $attributeCatalogueService;
    protected $attributeCatalogueRepository;
    protected $nestedset;
    protected $language;

    public function __construct(
        AttributeCatalogueService $attributeCatalogueService,
        AttributeCatalogueRepository $attributeCatalogueRepository
    ){
        $this->middleware(function($request, $next){
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });


        $this->attributeCatalogueService = $attributeCatalogueService;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
    }

    private function initialize(){
        $this->nestedset = new Nestedsetbie([
            'table' => 'attribute_catalogues',
            'foreignkey' => 'attribute_catalogue_id',
            'language_id' =>  $this->language,
        ]);
    } 
 
    public function index(Request $request){
        $this->authorize('modules', 'attribute.catalogue.index');
        $attributeCatalogues = $this->attributeCatalogueService->paginate($request, $this->language);
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
            'model' => 'AttributeCatalogue',
        ];
        $config['seo'] = __('message.attributeCatalogue');
        $template = 'backend.attribute.catalogue.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'attributeCatalogues'
        ));
    }

    public function create(){
        $this->authorize('modules', 'attribute.catalogue.create');
        $config = $this->configData();
        $config['seo'] = __('message.attributeCatalogue');
        $config['method'] = 'create';
        $dropdown  = $this->nestedset->Dropdown();
        $template = 'backend.attribute.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'dropdown',
            'config',
        ));
    }

    public function store(StoreAttributeCatalogueRequest $request){
        if($this->attributeCatalogueService->create($request, $this->language)){
            return redirect()->route('attribute.catalogue.index')->with('success','Thêm mới bản ghi thành công');
        }
        return redirect()->route('attribute.catalogue.index')->with('error','Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id){
        $this->authorize('modules', 'attribute.catalogue.update');
        $attributeCatalogue = $this->attributeCatalogueRepository->getAttributeCatalogueById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = __('message.attributeCatalogue');
        $config['method'] = 'edit';
        $dropdown  = $this->nestedset->Dropdown();
        $template = 'backend.attribute.catalogue.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown',
            'attributeCatalogue',
        ));
    }

    public function update($id, UpdateAttributeCatalogueRequest $request){
        if($this->attributeCatalogueService->update($id, $request, $this->language)){
            return redirect()->route('attribute.catalogue.index')->with('success','Cập nhật bản ghi thành công');
        }
        return redirect()->route('attribute.catalogue.index')->with('error','Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id){
        $this->authorize('modules', 'attribute.catalogue.destroy');
        $config['seo'] = __('message.attributeCatalogue');
        $attributeCatalogue = $this->attributeCatalogueRepository->getAttributeCatalogueById($id, $this->language);
        $template = 'backend.attribute.catalogue.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'attributeCatalogue',
            'config',
        ));
    }

    public function destroy(DeleteAttributeCatalogueRequest $request, $id){
        if($this->attributeCatalogueService->destroy($id, $this->language)){
            return redirect()->route('attribute.catalogue.index')->with('success','Xóa bản ghi thành công');
        }
        return redirect()->route('attribute.catalogue.index')->with('error','Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function configData(){
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
