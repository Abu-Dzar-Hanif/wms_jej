<?php

namespace App\Http\Controllers\editor;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index():View
    {
        return view('pages.editor.dashboard.index');
    }
}
