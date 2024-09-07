<?php

namespace App\Services;

use App\Services\Interfaces\AttributeCatalogueServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;

/**
 * Class AttributeCatalogueService
 * @package App\Services
 */
class AttributeCatalogueService extends BaseService implements AttributeCatalogueServiceInterface
{


    protected $attributeCatalogueRepository;
    protected $routerRepository;
    protected $nestedSet;
    protected $language;
    protected $controllerName = 'AttributeCatalogueController';
    

    public function __construct(
        AttributeCatalogueRepository $attributeCatalogueRepository,
        RouterRepository $routerRepository,
    ){
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->routerRepository = $routerRepository;
    }

    public function paginate($request, $languageId){
        $perPage = $request->integer('perpage');
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
            'where' => [
                ['tb2.language_id', '=', $languageId]
            ]
        ];
        $attributeCatalogues = $this->attributeCatalogueRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => 'attribute.catalogue.index'],  
            ['attribute_catalogues.lft', 'ASC'],
            [
                ['attribute_catalogue_language as tb2','tb2.attribute_catalogue_id', '=' , 'attribute_catalogues.id']
            ], 
            ['languages']
        );

        return $attributeCatalogues;
    }

    public function create($request, $languageId){
        DB::beginTransaction();
        try{
            $attributeCatalogue = $this->createCatalogue($request);
            if($attributeCatalogue->id > 0){
                $this->updateLanguageForCatalogue($attributeCatalogue, $request, $languageId);
                $this->createRouter($attributeCatalogue, $request, $this->controllerName, $languageId);
                $this->nestedSet = new Nestedsetbie([
                    'table' => 'attribute_catalogues',
                    'foreignkey' => 'attribute_catalogue_id',
                    'language_id' =>  $languageId ,
                ]);
                $this->nestedset();
            }
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    public function update($id, $request, $languageId){
        DB::beginTransaction();
        try{
            $attributeCatalogue = $this->attributeCatalogueRepository->findById($id);
            $flag = $this->updateCatalogue($attributeCatalogue, $request);
            if($flag == TRUE){
                $this->updateLanguageForCatalogue($attributeCatalogue, $request, $languageId);
                $this->updateRouter(
                    $attributeCatalogue, $request, $this->controllerName, $languageId
                );
                $this->nestedSet = new Nestedsetbie([
                    'table' => 'attribute_catalogues',
                    'foreignkey' => 'attribute_catalogue_id',
                    'language_id' =>  $languageId ,
                ]);
                $this->nestedset();
            }
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    public function destroy($id, $languageId){
        DB::beginTransaction();
        try{
            $attributeCatalogue = $this->attributeCatalogueRepository->forceDelete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\AttributeCatalogueController'],
            ]);
            $this->nestedSet = new Nestedsetbie([
                'table' => 'attribute_catalogues',
                'foreignkey' => 'attribute_catalogue_id',
                'language_id' =>  $languageId ,
            ]);
            $this->nestedset();
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    private function createCatalogue($request){
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $payload['user_id'] = Auth::id();
        $attributeCatalogue = $this->attributeCatalogueRepository->create($payload);
        return $attributeCatalogue;
    }

    private function updateCatalogue($attributeCatalogue, $request){
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $flag = $this->attributeCatalogueRepository->update($attributeCatalogue->id, $payload);
        return $flag;
    }

    private function updateLanguageForCatalogue($attributeCatalogue, $request, $languageId){
        $payload = $this->formatLanguagePayload($attributeCatalogue, $request, $languageId);
        $attributeCatalogue->languages()->detach([$languageId, $attributeCatalogue->id]);
        $language = $this->attributeCatalogueRepository->createPivot($attributeCatalogue, $payload, 'languages');
        return $language;
    }

    private function formatLanguagePayload($attributeCatalogue, $request, $languageId){
        $payload = $request->only($this->payloadLanguage());
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] =  $languageId;
        $payload['attribute_catalogue_id'] = $attributeCatalogue->id;
        return $payload;
    }

    public function updateStatus($post = []){
        DB::beginTransaction();
        try{
            $payload[$post['field']] = (($post['value'] == 1)?2:1);
            $postCatalogue = $this->attributeCatalogueRepository->update($post['modelId'], $payload);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    public function updateStatusAll($post){
        DB::beginTransaction();
        try{
            $payload[$post['field']] = $post['value'];
            $flag = $this->attributeCatalogueRepository->updateByWhereIn('id', $post['id'], $payload);

            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }
    

    private function paginateSelect(){
        return [
            'attribute_catalogues.id', 
            'attribute_catalogues.publish',
            'attribute_catalogues.image',
            'attribute_catalogues.level',
            'attribute_catalogues.order',
            'tb2.name', 
            'tb2.canonical',
        ];
    }

    private function payload(){
        return [
            'parent_id',
            'follow',
            'publish',
            'image',
            'album',
        ];
    }
    private function payloadLanguage(){
        return [
            'name',
            'description',
            'content',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'canonical'
        ];
    }


}
