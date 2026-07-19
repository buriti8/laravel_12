<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ToolController extends Controller
{
    public function cache()
    {
        try {
            set_time_limit(0);

            Artisan::call('config:cache');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', __('Error limpiando caché'));
        }

        return back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function phpinfo()
    {
        /* phpinfo(); */
    }
}
