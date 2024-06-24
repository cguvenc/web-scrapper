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
        $storeUrl = $request->input('store_url');
        $consumerKey = $request->input('consumer_key');
        $consumerSecret = $request->input('consumer_secret');
        $goalUrl = $request->input('goal_url');
        $productCount = $request->input('product_count');
        $reviewMin = $request->input('review_min');
        $reviewMax = $request->input('review_max');
        $categorieId = $request->input('categorie_id');

        $controller = new \App\Http\Controllers\Back\HepsiburadaController();
        $controller->index($storeUrl, $consumerKey, $consumerSecret, $goalUrl, $productCount, $reviewMin, $reviewMax, $categorieId);

        return response()->json([
            'status' => true,
            'message' => 'Ürünler başarıyla kuyruğa alındı.'
        ],200);
    }
}
