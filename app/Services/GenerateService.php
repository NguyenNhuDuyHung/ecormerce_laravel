<?php

namespace App\Services;

use App\Services\Interfaces\GenerateServiceInterface;
use App\Repositories\Interfaces\GenerateRepositoryInterface as GenerateRepository;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rules\Exists;

/**
 * Class GenerateService
 * @package App\Services
 */
class GenerateService implements GenerateServiceInterface
{
    protected $generateRepository;
    public function __construct(GenerateRepository $generateRepository)
    {
        $this->generateRepository = $generateRepository;
    }

    private function paginateSelect()
    {
        return ['id', 'name', 'schema'];
    }

    public function paginate($request)
    {
        $condition['keyword'] = addcslashes($request->input('keyword'), '\\%_');
        $condition['publish'] = $request->integer('publish');
        $perpage = $request->integer('perpage');
        $languages = $this->generateRepository
            ->pagination($this->paginateSelect(), $condition, $perpage, ['path' => 'language/index']);
        return $languages;
    }

    // make database + file migration
    private function createMigrationFile($payload)
    {
        $migrationTemplate = <<<MIGRATION
        <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        {$payload['schema']}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("{$this->convertModuleNameToTableName($payload['name'])}s"); 
    }
};


MIGRATION;
        return $migrationTemplate;
    }

    private function convertModuleNameToTableName($name)
    {
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        return $temp;
    }

    private function pivotSchema($tableName = '', $foreignKey = '', $pivot = '')
    {
        $pivotSchema = <<<SCHEMA
Schema::create('{$pivot}', function (Blueprint \$table) {
    \$table->unsignedBigInteger('{$foreignKey}');
    \$table->unsignedBigInteger('language_id');
    \$table->foreign('{$foreignKey}')->references('id')->on('{$tableName}')->onDelete('cascade');
    \$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
    \$table->string('name');
    \$table->text('description')->nullable();
    \$table->longText('content')->nullable();
    \$table->string('meta_title')->nullable();
    \$table->string('meta_keyword')->nullable();
    \$table->text('meta_description')->nullable();
    \$table->string('canonical')->nullable();
    \$table->timestamps();
});

SCHEMA;

        return $pivotSchema;
    }

    private function makeDatabase($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only('schema', 'name', 'module_type');
            $tableName = $this->convertModuleNameToTableName($payload['name']) . 's';
            $migrationFileName = date('Y_m_d_His') . '_create_' . $tableName . '_table.php';
            $migrationPath = database_path('migrations/' . $migrationFileName);
            $migrationTemplate = $this->createMigrationFile($payload);
            FILE::put($migrationPath, $migrationTemplate);

            if ($payload['module_type'] !== 3) {
                $foreignKey = $this->convertModuleNameToTableName($payload['name']) . '_id';
                $pivotTableName = $this->convertModuleNameToTableName($payload['name']) . '_language';
                $pivotSchema = $this->pivotSchema($tableName, $foreignKey, $pivotTableName);
                $migrationPivotTemplate = $this->createMigrationFile([
                    'schema' => $pivotSchema,
                    'name' => $pivotTableName,
                ]);
                $migrationPivotFileName = date('Y_m_d_His', time() + 10) . '_create_' . $pivotTableName . '_table.php';
                $migrationPivotPath = database_path('migrations/' . $migrationPivotFileName);
                FILE::put($migrationPivotPath, $migrationPivotTemplate);
            }
            ARTISAN::call('migrate');
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();
            return false;
        }
    }

    // make controller
    private function makeController($request)
    {
        $payload = $request->only('name', 'module_type');

        switch ($payload['module_type']) {
            case 1:
                $this->createTemplateController($payload['name'], 'TemplateCatalogueController');
                break;
            case 2:
                $this->createTemplateController($payload['name'], 'TemplateController');
                break;
            default:
                $this->createTemplateSingleController($payload['name']);
                break;
        }
    }

    private function createTemplateController($name, $controllerFileName)
    {
        try {
            $controllerName = $name . 'Controller';
            $templateControllerPath = base_path('app/Templates/' . $controllerFileName . '.php');
            $controllerContent = file_get_contents($templateControllerPath);
            $replace = [
                'ModuleTemplate' => $name,
                'moduleTemplate' => lcfirst($name),
                'foreignKey' => $this->convertModuleNameToTableName($name) . '_id',
                'moduleView' => str_replace('_', '.', $this->convertModuleNameToTableName($name)),
                'tableName' => $this->convertModuleNameToTableName($name) . 's',
            ];

            foreach ($replace as $key => $value) {
                $controllerContent = str_replace('{' . $key . '}', $value, $controllerContent);
            }
            $controllerPath = base_path('app/Http/Controllers/Backend/' . $controllerName . '.php');
            FILE::put($controllerPath, $controllerContent);
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // make model
    private function makeModel($request)
    {
        try {
            if ($request->input('module_type') == 1) {
                $this->createModelTemplate($request);
            } else {
                echo 1;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function createModelTemplate($request)
    {
        $templateModelPath = base_path('app/Templates/TemplateCatalogueModel.php');
        $modelContent = file_get_contents($templateModelPath);

        $extractModule = explode('_', $this->convertModuleNameToTableName($request->input('name')));

        $replace = [
            'ModuleTemplate' => $request->input('name'),
            'foreignKey' => $this->convertModuleNameToTableName($request->input('name')) . '_id',
            'tableName' => $this->convertModuleNameToTableName($request->input('name')) . 's',
            'relation' => $extractModule[0],
            'relationPivot' => $this->convertModuleNameToTableName($request->input('name')) . '_' . $extractModule[0],
            'pivotModel' => $request->input('name') . 'Language',
            'pivotTable' => $this->convertModuleNameToTableName($request->input('name')) . '_language',
            'module' => $this->convertModuleNameToTableName($request->input('name')),
            'relationModel' => ucfirst($extractModule[0]),
        ];

        foreach ($replace as $key => $value) {
            $modelContent = str_replace('{' . $key . '}', $value, $modelContent);
        }

        $modelName = $request->input('name') . '.php';
        $modelPath = base_path('app/Models/' . $modelName);
        FILE::put($modelPath, $modelContent);
    }

    // make repository
    private function makeRepository($request)
    {
        try {
            $name = $request->input('name');
            $repository = $this->initializeServiceLayer('Repository', 'Repositories', $request);
            $replaceRepositoryInterface = [
                'Module' => $name,
            ];
            $repositoryInterfaceContent = str_replace('{Module}', $replaceRepositoryInterface['Module'], $repository['interface']['layerInterfaceContent']);
            FILE::put($repository['interface']['layerInterfacePath'], $repositoryInterfaceContent);

            $replaceRepository = [
                'Module' => $name,
                'tableName' => $this->convertModuleNameToTableName($name),
                'tableNames' => $this->convertModuleNameToTableName($name) . 's',
                'pivotTableName' => $this->convertModuleNameToTableName($name) . '_' . 'language',
                'foreignKey' => $this->convertModuleNameToTableName($name) . '_id',
            ];

            $layerContent = $repository['Repository']['layerContent'];

            foreach ($replaceRepository as $key => $value) {
                $layerContent = str_replace('{' . $key . '}', $value, $layerContent);
            }

            FILE::put($repository['Repository']['layerPath'], $layerContent);
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // make service
    private function makeService($request)
    {
        try {
            $name = $request->input('name');
            $service = $this->initializeServiceLayer('Service', 'Services', $request);

            $replaceServiceInterface = [
                'Module' => $name,
            ];
            $serviceInterfaceContent = str_replace('{Module}', $replaceServiceInterface['Module'], $service['interface']['layerInterfaceContent']);
            FILE::put($service['interface']['layerInterfacePath'], $serviceInterfaceContent);

            $replaceService = [
                'Module' => $name,
                'module' => lcfirst($name),
                'tableName' => $this->convertModuleNameToTableName($name) . 's',
                'foreignKey' => $this->convertModuleNameToTableName($name) . '_id',
                'pivotTableName' => $this->convertModuleNameToTableName($name) . '_' . 'language',
            ];

            $layerContent = $service['Service']['layerContent'];
            foreach ($replaceService as $key => $value) {
                $layerContent = str_replace('{' . $key . '}', $value, $layerContent);
            }
            FILE::put($service['Service']['layerPath'], $layerContent);
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function initializeServiceLayer($layer = '', $folder = '', $request)
    {
        $name = $request->input('name');
        $option = [
            $layer . 'Name' => $name . $layer,
            $layer . 'InterfaceName' => $name . $layer . 'Interface',
        ];

        $templateRepositoryInterfacePath = base_path('app/Templates/Template' . $layer . 'Interface.php');
        $layerInterfaceContent = file_get_contents($templateRepositoryInterfacePath);
        $layerInterfacePath = base_path('app/' . $folder . '/Interfaces/' . $option[$layer . 'InterfaceName'] . '.php');

        $templateRepositoryPath = base_path('app/Templates/Template' . $layer . '.php');
        $layerContent = file_get_contents($templateRepositoryPath);
        $layerPath = base_path('app/' . $folder . '/' . $option[$layer . 'Name'] . '.php');
        return [
            'interface' => [
                'layerInterfaceContent' => $layerInterfaceContent,
                'layerInterfacePath' => $layerInterfacePath
            ],
            $layer => [
                'layerContent' => $layerContent,
                'layerPath' => $layerPath,
            ]
        ];
    }

    // make provider
    private function makeProvider($request)
    {
        try {
            $name = $request->input('name');

            $providerPath = [
                'serviceProviderPath' => base_path('app/Providers/AppServiceProvider.php'),
                'repositoryServiceProviderPath' => base_path('app/Providers/RepositoryServiceProvider.php'),
            ];
            foreach ($providerPath as $key => $value) {
                $content = file_get_contents($value);
                $insertLine = $key == 'serviceProviderPath' ? "'App\Services\Interfaces\\" . $name . "ServiceInterface' => 'App\Services\\" . $name . "Service',"
                    : "'App\Repositories\Interfaces\\" . $name . "RepositoryInterface' => 'App\Repositories\\" . $name . "Repository',";

                $position = strpos($content, '];');

                if ($position !== false) {
                    $newContent = substr_replace($content, $insertLine, $position, 0);
                    FILE::put($value, $newContent);
                }
            }
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // make request
    private function makeRequest($request)
    {
        try {
            // StoreModuleRequest UpdateModuleRequest DeleteModuleRequest
            $name = $request->input('name');
            $requestArray = [
                'Store' . $name . 'Request',
                'Update' . $name . 'Request',
                'Delete' . $name . 'Request',
            ];

            $templateRequest = [
                'TemplateStoreModuleRequest',
                'TemplateUpdateModuleRequest',
                'TemplateDeleteModuleRequest',
            ];

            if ($request->input('module_type') != 1) {
                unset($requestArray[2]);
                unset($templateRequest[2]);
            }

            foreach ($templateRequest as $key => $value) {
                $requestTemplatePath = base_path('app/Templates/' . $value . '.php');
                $requestContent = file_get_contents($requestTemplatePath);
                $requestContent = str_replace('{Module}', $name, $requestContent);
                $requestPath = base_path('app/Http/Requests/' . $requestArray[$key] . '.php');

                FILE::put($requestPath, $requestContent);
            }
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // make view
    private function makeView($request)
    {

        try {
            $name = $request->input('name');
            $module = $this->convertModuleNameToTableName($name);
            $extractModule = explode('_', $module);

            $basePath = resource_path('views/backend/' . $extractModule[0]);
            $folderPath = count($extractModule) == 2 ? "$basePath/{$extractModule[1]}" : "$basePath/{$extractModule[0]}";

            $componentsPath = "$folderPath/components";

            $this->createDirectory($folderPath);
            $this->createDirectory($componentsPath);

            $replacement = [
                'view' => count($extractModule) == 2 ? "{$extractModule[0]}.{$extractModule[1]}" : $extractModule[0],
                'module' => lcfirst($name),
                'Module' => $name,
            ];

            $sourcePath = base_path("app/Templates/view/" . (count($extractModule) == 2 ? 'catalogue' : 'detail') . '/');

            $fileArray = [
                'store.blade.php',
                'index.blade.php',
                'delete.blade.php'
            ];

            $componentsFileArray = [
                'aside.blade.php',
                'filter.blade.php',
                'table.blade.php',
            ];

            $this->CopyAndReplaceContent($sourcePath, $folderPath, $fileArray, $replacement);
            $this->CopyAndReplaceContent("{$sourcePath}components/", $componentsPath, $componentsFileArray, $replacement);
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function CopyAndReplaceContent(string $sourcePath, string $destinationPath, array $fileArray, array $replacement)
    {
        foreach ($fileArray as $key => $value) {
            $content = file_get_contents($sourcePath . $value);
            $destination = "$destinationPath/$value";
            foreach ($replacement as $key => $value) {
                $content = str_replace('{' . $key . '}', $value, $content);
            }
            if (!FILE::exists($destination)) {
                FILE::put($destination, $content);
            }
        }
    }

    private function createDirectory($path)
    {
        if (!FILE::exists($path)) {
            FILE::makeDirectory($path, 0755, true);
        }
    }

    // make routes
    private function makeRoutes($request)
    {
        $name = $request->input('name');
        $module = $this->convertModuleNameToTableName($name);
        $moduleExtract = explode('_', $module);
        $routesPath = base_path('routes/web.php');
        $content = file_get_contents($routesPath);

        $routeUrl = count($moduleExtract) == 2 ? $moduleExtract[0] . '/' . $moduleExtract[1] : $moduleExtract[1];
        $routeName = count($moduleExtract) == 2 ? $moduleExtract[0] . '.' . $moduleExtract[1] : $moduleExtract[0];

        $routeGroup = <<<ROUTE
Route::group(["prefix" => "{$routeUrl}"], function () {
        Route::get("index", [{$name}Controller::class, "index"])->name("{$routeName}.index");
        Route::get("create", [{$name}Controller::class, "create"])->name("{$routeName}.create");
        Route::post("store", [{$name}Controller::class, "store"])->name("{$routeName}.store");
        Route::get("edit/{id}", [{$name}Controller::class, "edit"])->where("id", "[0-9]+")->name("{$routeName}.edit");
        Route::post("update/{id}", [{$name}Controller::class, "update"])->where("id", "[0-9]+")->name("{$routeName}.update");
        Route::get("delete/{id}", [{$name}Controller::class, "delete"])->where("id", "[0-9]+")->name("{$routeName}.delete");
        Route::post("destroy/{id}", [{$name}Controller::class, "destroy"])->where("id", "[0-9]+")->name("{$routeName}.destroy");
    });

    //@@new-module@@
ROUTE;
        $useController = <<<ROUTE
use App\Http\Controllers\Backend\\{$name}Controller;
//@@useController@@

ROUTE;

        $content = str_replace('//@@new-module@@', $routeGroup, $content);
        $content = str_replace('//@@useController@@', $useController, $content);
        FILE::put($routesPath, $content);
    }

    public function create(Request $request)
    {
        try {
            $database = $this->makeDatabase($request);
            $controller = $this->makeController($request);
            $model = $this->makeModel($request);
            $repository = $this->makeRepository($request);
            $service = $this->makeService($request);
            $provider = $this->makeProvider($request);
            $makeRequest = $this->makeRequest($request);
            $view = $this->makeView($request);
            $routes = $this->makeRoutes($request);
            // $this->makeRule();
            // $this->makeLang();
            // DB::beginTransaction();
            $payload = $request->except(['_token', 'send']);
            $payload['user_id'] = Auth::id();
            $this->generateRepository->create($payload);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage().'-'.$e->getLine();die();
            return false;
        }
    }

    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $this->generateRepository->update($id, $payload);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();
            return false;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->generateRepository->forceDelete($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();
            return false;
        }
    }
}
