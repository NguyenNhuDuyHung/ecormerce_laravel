<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

/**
 * Interface PermissionServiceInterface
 * @package App\Services\Interfaces
 */
interface PermissionServiceInterface
{
    public function paginate($request);
    public function create(Request $request);
    public function update($id, Request $request);

    public function destroy($id);
}
