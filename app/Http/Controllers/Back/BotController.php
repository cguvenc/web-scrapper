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

    public function store(Request $request)
    {
        $controller = new \App\Http\Controllers\Back\HepsiburadaController();
        $controller->index($request->store_url,$request->consumer_key,$request->consumer_secret,$request->goal_url,$request->product_count,$request->review_count,$request->categorie_id);
    }
}
