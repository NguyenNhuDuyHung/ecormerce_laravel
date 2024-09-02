<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

/**
 * Interface UserCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface UserCatalogueServiceInterface
{
    public function paginate($request);
    public function create(Request $request);
    public function update($id, Request $request);

    public function destroy($id);

    public function setPermission(Request $request);
}
