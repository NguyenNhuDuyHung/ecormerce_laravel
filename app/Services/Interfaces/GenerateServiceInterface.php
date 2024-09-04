<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

/**
 * Interface GenerateServiceInterface
 * @package App\Services\Interfaces
 */
interface GenerateServiceInterface
{
    public function paginate(Request $request);
    public function create(Request $request);
    public function update($id, Request $request);
    public function destroy($id);
}
