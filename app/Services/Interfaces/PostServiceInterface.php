<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

/**
 * Interface PostServiceInterface
 * @package App\Services\Interfaces
 */
interface PostServiceInterface
{
    public function paginate($request, $language);
    public function create(Request $request, $language);
    public function update($id, Request $request, $language);

    public function destroy($id);
}
