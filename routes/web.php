<?php

use App\Http\Controllers\zones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['login'])->group(function () {
    Route::get('/', [zones::class, 'viewZone']);
    Route::post('/zone/add', [zones::class, 'addZone']);

    Route::get('/zone/{name}', [zones::class, 'editZone']);
    Route::get('/zone/{name}/delete', [zones::class, 'deleteZone']);
    Route::post('/zone/record/{name}/add', [zones::class, 'addRecord']);
    Route::get('/zone/record/delete/{id}', [zones::class, 'deleteRecord']);
    Route::post('/zone/record/edit/{id}', [zones::class, 'editRecord']);

    Route::post('/update/peers', [zones::class, 'updatePeers']);

    Route::get('/options', [zones::class, 'viewOptions']);
    Route::post('/options', [zones::class, 'options']);

    Route::post('/ptr/add', [zones::class, 'addPtr']);
    Route::post('/ptr/record/add', [zones::class, 'addRecordPTR']);
    Route::post('/ptr/record/edit', [zones::class, 'editRecordPTR']);
    Route::get('/ptr/record/delete/{id}', [zones::class, 'deleteRecordPTR']);

    Route::get('/ptr/{ptr}', [zones::class, 'viewZonePTR']);
    Route::get('/ptr/{ptr}/delete', [zones::class, 'deleteZonePTR']);

    Route::get('/setting', [zones::class, 'viewSetting']);
    Route::post('/setting', [zones::class, 'setting']);
});

Route::get('/login', function () {
    if (session()->has('logged')) {
        return redirect('/');
    } else {
        return view('layouts.login');
    }
});

Route::get('/logout', function () {
    session()->flush();
    return redirect('/login');
});

Route::post('/login', function (Request $request) {
    $account = DB::table('tb_account')->where('username', $request->username)->where('password', $request->password);

    if ($account->count() > 0) {
        session(['logged' => 'true']);
        return redirect('/');
    } else {
        return redirect('/login');
    }
});
