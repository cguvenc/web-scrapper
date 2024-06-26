<?php

namespace App\Http\Controllers\back;

use App\Models\Market;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $jobs = DB::table('jobs')->get()->map(function ($job) {
            $payload = json_decode($job->payload);
            if (isset($payload->data->command)) {
                $command = unserialize($payload->data->command);
                if (isset($command->url)) {
                    $job->url = $command->url;
                } else {
                    $job->url = 'N/A';
                }
            } else {
                $job->url = 'N/A';
            }
            return $job;
        });

        return view('back.pages.dashboard.index',compact('jobs'));
    }
}
