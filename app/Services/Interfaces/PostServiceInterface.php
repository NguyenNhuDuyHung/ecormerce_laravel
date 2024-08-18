<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

/**
 * Interface PostServiceInterface
 * @package App\Services\Interfaces
 */
interface PostServiceInterface
{
    public function paginate($request);
    public function create(Request $request);
    public function update($id, Request $request);

    public function destroy($id);
}
