<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
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

        return view('back.pages.job.index',compact('jobs'));
    }


    public function fail()
    {
        $failedJobs = DB::table('failed_jobs')->get()->map(function ($job) {
            $payload = json_decode($job->payload);
            if (isset($payload->data->command)) {
                $command = unserialize($payload->data->command);
                $job->url = isset($command->url) ? $command->url : 'N/A';
            } else {
                $job->url = 'N/A';
            }
            
            return $job;
        });

        return view('back.pages.job.fail', compact('failedJobs'));
    }

    public function destroy(string $id)
    {
        $job = DB::table('jobs')->where('id', $id)->first();

        if (!$job) {
            abort(404, 'Job not found');
        }

        DB::table('jobs')->where('id', $id)->delete();

        return redirect()->back()->with('success','Görev Başarıyla silindi.');
    }
}
