<?php

use App\Models\Blog;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Http;

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

Route::get('/', function () {
    $blogs = Blog::with('images')->get();
    return view('blog_list', compact('blogs'));
});
Route::get('/test_gmail',function(){
  return Http::get('http://localhost:3500/gmail')->json();
});
Route::get('/blogs/create', function () {
    return view('welcome');
});

Route::post('blogs', function (Request $request) {
    $request->validate([
        'title' => 'required',
        'content' => 'required',
        'author' => 'required',
    ]);
    // return $request->all();
    $blog = Blog::create([
        'title' => $request->title,
        'content' => $request->content,
        'author' => $request->author,
        'category'=>$request->category,
        'blog_date'=>$request->date
    ]);
    if ($request->hasFile('images')) {

        foreach ($request->file('images') as $image) {

            $image_name = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('/images'), $image_name);

            Image::create([
                'blog_id' => $blog->id,
                'url' => url("images/$image_name")
            ]);
        }
    }
    return redirect()->to('/')->with('success',"Blog Added!");
})->name('store');

Route::delete('/blogs/{id}',function($id){
    Blog::findOrFail($id)->delete();
    return redirect()->to('/')->with('success',"Blog Deleted!");

})->name('delete_blog');
