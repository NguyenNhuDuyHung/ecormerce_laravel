<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

/**
 * Interface LanguageServiceInterface
 * @package App\Services\Interfaces
 */
interface LanguageServiceInterface
{
    public function paginate($request);
    public function create(Request $request);
    public function update($id, Request $request);

    public function destroy($id);
}
