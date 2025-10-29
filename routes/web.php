<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\PosController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/run-artisan-commands', function () {
    Artisan::call('db:wipe');
    Artisan::call('migrate');
    Artisan::call('permission:create-permission-routes');
    Artisan::call('db:seed');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return 'All commands executed successfully!';
});

Route::group(['namespace' => 'Admin', 'as' => 'admin.'], function () {
    Route::group(['middleware' => ['admin']], function () {
        Route::get('/', 'SystemController@dashboard')->name('dashboard');
    });
});

Route::get('time_zome', function () {
    return config('app.timezone');
});


Route::get('/test-pdf', function () {
    $filePath = public_path('sample.pdf'); // Make sure the path to your PDF is correct

    // Check if the file exists
    if (!file_exists($filePath)) {
        return response()->json(['error' => 'File not found.'], 404);
    }

    return response()->download($filePath, 'sample.pdf', [], 'inline');


    // return Response::make(file_get_contents($filePath), 200, [
    //     'Content-Type' => 'application/pdf',
    //     'Content-Disposition' => 'inline; filename="'.$filePath.'"'
    // ]);
});




