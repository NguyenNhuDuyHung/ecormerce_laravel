<?php

namespace App\Services\Interfaces;

/**
 * Interface AttributeServiceInterface
 * @package App\Services\Interfaces
 */
interface AttributeCatalogueServiceInterface extends BaseServiceInterface
{
    public function paginate($request, $languageId);
    public function create($request, $languageId);
    public function update($id, $request, $languageId);
    public function destroy($id, $languageId);
}
