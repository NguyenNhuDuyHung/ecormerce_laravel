<?php

namespace App\Services;

use App\Services\Interfaces\GenerateServiceInterface;
use App\Repositories\Interfaces\GenerateRepositoryInterface as GenerateRepository;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

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
        Schema::dropIfExists('{$payload['name']}'); 
    }
};


MIGRATION;
        dd($migrationTemplate);
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
});

SCHEMA;

        return $pivotSchema;
    }

    private function makeDatabase($request)
    {
        // create database
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


        // ARTISAN::call('migrate');
        die();
        // create file migration

    }

    public function create(Request $request)
    {

        $this->makeDatabase($request);
        // $this->makeController();
        // $this->makeModel(); 
        // $this->makeRepository();
        // $this->makeService();
        // $this->makeProvider();
        // $this->makeRequest();
        // $this->makeView();
        // $this->makeRoutes();
        // $this->makeRule();
        // $this->makeLang();


        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $payload['user_id'] = Auth::id();
            $this->generateRepository->create($payload);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error($e->getMessage());
            echo $e->getMessage();
            die();
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
            die();
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
            die();
            return false;
        }
    }

}