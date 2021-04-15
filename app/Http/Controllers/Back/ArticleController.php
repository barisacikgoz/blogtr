<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('created_at', 'ASC')->get();
        return view('back.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('back.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'min:3',
            'image' => 'image|required|mimes:jpeg,png,jpg|max:2048'
        ]);

        $article = new Article;
        $article->title = $request->post('title');
        $article->category_id = $request->post('category');
        $article->content = $request->post('content');
        $article->slug = Str::slug($request->post('title'));

        if ($request->hasFile('image')) {
            $imageName = Str::slug($request->post('title')) . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads'), $imageName);
            $article->image = 'uploads/' . $imageName;
        }
        $article->save();
        toastr()->success('Başarılı', 'Makale Başarıyla oluşturuldu');
        return redirect()->route('admin.makaleler.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $categories = Category::all();
        return view('back.articles.update', compact('categories', 'article'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'min:3',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $article = Article::findOrFail($id);
        $article->title = $request->post('title');
        $article->category_id = $request->post('category');
        $article->content = $request->post('content');
        $article->slug = Str::slug($request->post('title'));

        if ($request->hasFile('image')) {
            $imageName = Str::slug($request->post('title')) . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads'), $imageName);
            $article->image = 'uploads/' . $imageName;
        }
        $article->save();
        toastr()->success('Başarılı', 'Makale Güncellendi');
        return redirect()->route('admin.makaleler.index');
    }

    public function switch(Request $request)
    {
        $article=Article::findOrFail($request->id);
        $article->status=$request->statu == "true" ? 1 : 0;
        $article->save();
    }

    public function delete($id){
        Article::find($id)->delete();
        toastr()->success('Makale Başarıyla silindi.');
        return redirect()->route('admin.makaleler.index');
    }

    public function trashed(){
        $articles= Article::onlyTrashed()->orderBy('deleted_at', 'ASC')->get();
        return view('back.articles.trashed', compact('articles'));
    }

    public function recover($id){
        Article::onlyTrashed()->find($id)->restore();
        toastr()->success('Makale Geri Yüklendi');
        return redirect()->back();
    }

    public function destroy($id)
    {

    }

    public function hardDelete($id){
        $article = Article::onlyTrashed()->find($id);
        if (File::exists($article->image)){
            File::delete(public_path($article->image));
        }
        $article->forceDelete();
        toastr()->success('Makale kalıcı olarak silindi.');
        return redirect()->route('admin.makaleler.index');
    }

}
