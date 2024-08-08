<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
    }

    public function changeStatus(Request $request)
    {
        $status = $request->input();

        // Gọi service động (model)
        $serviceInterfaceNamespace = 'App\Services\\' . ucfirst($status['model']) . 'Service';
        if (class_exists($serviceInterfaceNamespace)) {
            $serviceInstance = app($serviceInterfaceNamespace);
        }

        // fixx errorInternal Server Error
        $flag = $serviceInstance->updateStatus($status);
        return response()->json(['flag' => $flag]);
    }

    public function changeStatusAll(Request $request)
    {
        $status = $request->input();
        $serviceInterfaceNamespace = 'App\Services\\' . ucfirst($status['model']) . 'Service';
        if (class_exists($serviceInterfaceNamespace)) {
            $serviceInstance = app($serviceInterfaceNamespace);
        }
        // fixx errorInternal Server Error
        $flag = $serviceInstance->updateStatusAll($status);
        return response()->json(['flag' => $flag]);
    }
}
