<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\ProductServiceInterface as ProductService;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\DeleteProductRequest;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class ProductController extends Controller
{
    protected $productService;
    protected $productRepository;
    protected $languageRepository;
    protected $language;
    protected $attributeCatalogueRepository;

    public function __construct(
        ProductService $productService,
        ProductRepository $productRepository,
        AttributeCatalogueRepository $attributeCatalogueRepository
    ) {
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale(); // vn en cn
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });

        $this->productService = $productService;
        $this->productRepository = $productRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->initialize();

    }

    private function initialize()
    {
        $this->nestedset = new Nestedsetbie([
            'table' => 'product_catalogues',
            'foreignkey' => 'product_catalogue_id',
            'language_id' => $this->language,
        ]);
    }

    public function index(Request $request)
    {
        $this->authorize('modules', 'product.index');
        $products = $this->productService->paginate($request, $this->language);
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
            'model' => 'Product'
        ];
        $config['seo'] = __('message.product');
        $template = 'backend.product.product.index';
        $dropdown = $this->nestedset->Dropdown();
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown',
            'products'
        ));
    }

    public function create()
    {
        $this->authorize('modules', 'product.create');

        $attributeCatalogue = $this->attributeCatalogueRepository->getAllAttributeTranslations($this->language);
        $config = $this->configData();
        $config['seo'] = __('message.product');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.product.product.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'dropdown',
            'config',
            'attributeCatalogue',
        ));
    }

    public function store(StoreProductRequest $request)
    {
        if ($this->productService->create($request, $this->language)) {
            return redirect()->route('product.index')->with('success', 'Thêm mới bản ghi thành công');
        }
        return redirect()->route('product.index')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id)
    {
        $this->authorize('modules', 'product.update');
        $product = $this->productRepository->getProductById($id, $this->language);
        $attributeCatalogue = $this->attributeCatalogueRepository->getAllAttributeTranslations($this->language);
        $config = $this->configData();
        $config['seo'] = __('message.product');
        $config['method'] = 'edit';
        $dropdown = $this->nestedset->Dropdown();
        $album = json_decode($product->album);
        $template = 'backend.product.product.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown',
            'product',
            'album',
            'attributeCatalogue',
        ));
    }

    public function update($id, UpdateProductRequest $request)
    {
        if ($this->productService->update($id, $request, $this->language)) {
            return redirect()->route('product.index')->with('success', 'Cập nhật bản ghi thành công');
        }
        return redirect()->route('product.index')->with('error', 'Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id)
    {
        $this->authorize('modules', 'product.destroy');
        $config['seo'] = __('message.product');
        $product = $this->productRepository->getProductById($id, $this->language);
        $template = 'backend.product.product.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'product',
            'config',
        ));
    }

    public function destroy($id)
    {
        if ($this->productService->destroy($id, $this->language)) {
            return redirect()->route('product.index')->with('success', 'Xóa bản ghi thành công');
        }
        return redirect()->route('product.index')->with('error', 'Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function configData()
    {
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'backend/css/plugins/switchery/switchery.css',
                'backend/plugins/nice-select/css/nice-select.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/select2.js',
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/library/seo.js',
                'backend/plugins/nice-select/js/jquery.nice-select.min.js',
                'backend/js/plugins/switchery/switchery.js',
                'backend/library/switchery.js',
                'backend/library/variant.js', 
            ]
        ];
    }



}
