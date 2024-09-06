<?php

namespace App\Services;

use App\Services\Interfaces\{$class}CatalogueServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\{$class}CatalogueRepositoryInterface as {$class}CatalogueRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;

/**
 * Class {$class}CatalogueService
 * @package App\Services
 */
class {$class}CatalogueService extends BaseService implements {$class}CatalogueServiceInterface
{


    protected ${module}CatalogueRepository;
    protected $routerRepository;
    protected $nestedset;
    protected $language;
    protected $controllerName = '{$class}CatalogueController';
    

    public function __construct(
        {$class}CatalogueRepository ${module}CatalogueRepository,
        RouterRepository $routerRepository,
    ){
        $this->{module}CatalogueRepository = ${module}CatalogueRepository;
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
        ${module}Catalogues = $this->{module}CatalogueRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => '{module}.catalogue.index'],  
            ['{module}_catalogues.lft', 'ASC'],
            [
                ['{module}_catalogue_language as tb2','tb2.{module}_catalogue_id', '=' , '{module}_catalogues.id']
            ], 
            ['languages']
        );

        return ${module}Catalogues;
    }

    public function create($request, $languageId){
        DB::beginTransaction();
        try{
            ${module}Catalogue = $this->createCatalogue($request);
            if(${module}Catalogue->id > 0){
                $this->updateLanguageForCatalogue(${module}Catalogue, $request, $languageId);
                $this->createRouter(${module}Catalogue, $request, $this->controllerName);
                $this->nestedset = new Nestedsetbie([
                    'table' => '{module}_catalogues',
                    'foreignkey' => '{module}_catalogue_id',
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
            ${module}Catalogue = $this->{module}CatalogueRepository->findById($id);
            $flag = $this->updateCatalogue(${module}Catalogue, $request);
            if($flag == TRUE){
                $this->updateLanguageForCatalogue(${module}Catalogue, $request, $languageId);
                $this->updateRouter(
                    ${module}Catalogue, $request, $this->controllerName
                );
                $this->nestedset = new Nestedsetbie([
                    'table' => '{module}_catalogues',
                    'foreignkey' => '{module}_catalogue_id',
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
            ${module}Catalogue = $this->{module}CatalogueRepository->delete($id);
            $this->nestedset = new Nestedsetbie([
                'table' => '{module}_catalogues',
                'foreignkey' => '{module}_catalogue_id',
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
        ${module}Catalogue = $this->{module}CatalogueRepository->create($payload);
        return ${module}Catalogue;
    }

    private function updateCatalogue(${module}Catalogue, $request){
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $flag = $this->{module}CatalogueRepository->update(${module}Catalogue->id, $payload);
        return $flag;
    }

    private function updateLanguageForCatalogue(${module}Catalogue, $request, $languageId){
        $payload = $this->formatLanguagePayload(${module}Catalogue, $request, $languageId);
        ${module}Catalogue->languages()->detach([$languageId, ${module}Catalogue->id]);
        $language = $this->{module}CatalogueRepository->createPivot(${module}Catalogue, $payload, 'languages');
        return $language;
    }

    private function formatLanguagePayload(${module}Catalogue, $request, $languageId){
        $payload = $request->only($this->payloadLanguage());
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] =  $languageId;
        $payload['{module}_catalogue_id'] = ${module}Catalogue->id;
        return $payload;
    }

    public function updateStatus($post = []){
        DB::beginTransaction();
        try{
            $payload[$post['field']] = (($post['value'] == 1)?2:1);
            $postCatalogue = $this->{module}CatalogueRepository->update($post['modelId'], $payload);
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
            $flag = $this->{module}CatalogueRepository->updateByWhereIn('id', $post['id'], $payload);

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
            '{module}_catalogues.id', 
            '{module}_catalogues.publish',
            '{module}_catalogues.image',
            '{module}_catalogues.level',
            '{module}_catalogues.order',
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
