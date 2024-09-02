<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

/**
 * Interface PostCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface PostCatalogueServiceInterface
{
    public function paginate($request, $languageId);
    public function create(Request $request, $languageId);
    public function update($id, Request $request, $languageId);

    public function destroy($id, $languageId);
}
