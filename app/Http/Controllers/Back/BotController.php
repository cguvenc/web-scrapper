<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Website;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function index()
    {
        $websites = Website::query()->get();
        return view('back.pages.bot.index',compact('websites'));
    }
}
