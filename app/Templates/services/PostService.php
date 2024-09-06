<?php

namespace App\Services;

use App\Services\Interfaces\{$class}ServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\{$class}RepositoryInterface as {$class}Repository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class {$class}Service
 * @package App\Services
 */
class {$class}Service extends BaseService implements {$class}ServiceInterface
{
    protected ${module}Repository;
    protected $routerRepository;
    
    public function __construct(
        {$class}Repository ${module}Repository,
        RouterRepository $routerRepository,
    ){
        $this->{module}Repository = ${module}Repository;
        $this->routerRepository = $routerRepository;
        $this->controllerName = '{$class}Controller';
    }

    public function paginate($request, $languageId){
        $perPage = $request->integer('perpage');
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
            'where' => [
                ['tb2.language_id', '=', $languageId],
            ],
        ];
        $paginationConfig = [
            'path' => '{module}.index', 
            'groupBy' => $this->paginateSelect()
        ];
        $orderBy = ['{module}s.id', 'DESC'];
        $relations = ['{module}_catalogues'];
        $rawQuery = $this->whereRaw($request, $languageId);
        // dd($rawQuery);
        $joins = [
            ['{module}_language as tb2', 'tb2.{module}_id', '=', '{module}s.id'],
            ['{module}_catalogue_{module} as tb3', '{module}s.id', '=', 'tb3.{module}_id'],
        ];

        ${module}s = $this->{module}Repository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            $paginationConfig,  
            $orderBy,
            $joins,  
            $relations,
            $rawQuery
        ); 
        return ${module}s;
    }

    public function create($request, $languageId){
        DB::beginTransaction();
        try{
            ${module} = $this->create{$class}($request);
            if(${module}->id > 0){
                $this->updateLanguageFor{$class}(${module}, $request, $languageId);
                $this->updateCatalogueFor{$class}(${module}, $request);
                $this->createRouter(${module}, $request, $this->controllerName);
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
            ${module} = $this->{module}Repository->findById($id);
            if($this->upload{$class}(${module}, $request)){
                $this->updateLanguageFor{$class}(${module}, $request, $languageId);
                $this->updateCatalogueFor{$class}(${module}, $request);
                $this->updateRouter(
                    ${module}, $request, $this->controllerName
                );
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

    public function destroy($id){
        DB::beginTransaction();
        try{
            ${module} = $this->{module}Repository->delete($id);
            
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            // echo $e->getMessage();die();
            return false;
        }
    }

    private function create{$class}($request){
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($request);
        ${module} = $this->{module}Repository->create($payload);
        return ${module};
    }

    private function upload{$class}(${module}, $request){
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        return $this->{module}Repository->update(${module}->id, $payload);
    }

    private function updateLanguageFor{$class}(${module}, $request, $languageId){
        $payload = $request->only($this->payloadLanguage());
        $payload = $this->formatLanguagePayload($payload, ${module}->id, $languageId);
        ${module}->languages()->detach([$this->language, ${module}->id]);
        return $this->{module}Repository->createPivot(${module}, $payload, 'languages');
    }

    private function updateCatalogueFor{$class}(${module}, $request){
        ${module}->{module}_catalogues()->sync($this->catalogue($request));
    }

    private function formatLanguagePayload($payload, ${module}Id, $languageId){
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] =  $languageId;
        $payload['{module}_id'] = ${module}Id;
        return $payload;
    }


    private function catalogue($request){
        if($request->input('catalogue') != null){
            return array_unique(array_merge($request->input('catalogue'), [$request->{module}_catalogue_id]));
        }
        return [$request->{module}_catalogue_id];
    }
    
    public function updateStatus($post = []){
        DB::beginTransaction();
        try{
            $payload[$post['field']] = (($post['value'] == 1)?2:1);
            $post = $this->{module}Repository->update($post['modelId'], $payload);
            // $this->changeUserStatus($post, $payload[$post['field']]);

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
            $flag = $this->{module}Repository->updateByWhereIn('id', $post['id'], $payload);
            // $this->changeUserStatus($post, $post['value']);

            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();die();
            return false;
        }
    }

    private function whereRaw($request, $languageId){
        $rawCondition = [];
        if($request->integer('{module}_catalogue_id') > 0){
            $rawCondition['whereRaw'] =  [
                [
                    'tb3.{module}_catalogue_id IN (
                        SELECT id
                        FROM {module}_catalogues
                        JOIN {module}_catalogue_language ON {module}_catalogues.id = {module}_catalogue_language.{module}_catalogue_id
                        WHERE lft >= (SELECT lft FROM {module}_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM {module}_catalogues as pc WHERE pc.id = ?)
                        AND {module}_catalogue_language.language_id = '.$languageId.'
                    )',
                    [$request->integer('{module}_catalogue_id'), $request->integer('{module}_catalogue_id')]
                ]
            ];
            
        }
        return $rawCondition;
    }

    private function paginateSelect(){
        return [
            '{module}s.id', 
            '{module}s.publish',
            '{module}s.image',
            '{module}s.order',
            'tb2.name', 
            'tb2.canonical',
        ];
    }

    private function payload(){
        return [
            'follow',
            'publish',
            'image',
            'album',
            '{module}_catalogue_id',
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
