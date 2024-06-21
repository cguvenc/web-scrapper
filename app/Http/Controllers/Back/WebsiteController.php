<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\back\StoreWebsiteRequest;
use App\Http\Requests\back\UpdateWebsiteRequest;
use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        $websites = Website::query()->get();
        $modals = ['create' => ['store_url','consumer_key','consumer_secret'],'edit' => ['update_store_url','update_consumer_key','update_consumer_secret']];
        return view('back.pages.website.index',compact('websites','modals'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebsiteRequest $request)
    {
        Website::create([
            'store_url' => $request->store_url,
            'consumer_key' => $request->consumer_key,
            'consumer_secret' => $request->consumer_secret
        ]);

        return redirect()->back()->with('success','Websitesi başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $website = Website::query()->where('id',$id)->first();

        return response()->json([
            'website' => $website
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteRequest $request, string $id)
    {
        $website = Website::query()->where('id',$id)->firstOrFail();

        $website->update([
            'store_url' => $request->update_store_url,
            'consumer_key' => $request->update_consumer_key,
            'consumer_secret' => $request->update_consumer_secret
        ]);

        return redirect()->back()->with('success','Websitesi başarıyla güncellendi.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $website = Website::query()->where('id',$id)->firstOrFail();
        $website->delete();

        return redirect()->back()->with('success','Websitesi başarıyla silindi.');
    }
}
