<?php

namespace App\Http\Controllers\back;

use App\Models\Market;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;


class DashboardController extends Controller
{
    public function index()
    {
        return view('back.pages.dashboard.index');
    }
}
