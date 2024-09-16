<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\System;
class SystemController extends Controller
{
    protected $systemLibrary;
    public function __construct(System $systemLibrary)
    {
        $this->systemLibrary = $systemLibrary;
    }

    public function index()
    {
        $system = $this->systemLibrary->config();
        $config = $this->config();
        $config['seo'] = __('message.system');
        $template = 'backend.system.index';
        return view("backend.dashboard.layout", compact("template", 'config', 'system'));
    }

    private function config()
    {
        return [
            'js' => [
                'backend/library/finder.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
            ]
        ];
    }
}
