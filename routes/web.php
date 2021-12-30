<?php

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::get('file/serve/{name}', function ($name) {
    $file = File::where('name', $name)->first();
    if ($file) {
        $filepath = storage_path('images/' . $file->name);
        $f = (Storage::get('images/' . $file->name));
        // return response()->file(Storage::url('images/' . $file->name));
        return (new Response($f, 200))->header('Content-Type', 'image/jpeg');
    }
    return response()->redirectTo('https://live.staticflickr.com/4205/35047763926_6e8ca0e027_c.jpg');
});

Route::get('/', function () {
    return view('welcome');
});
