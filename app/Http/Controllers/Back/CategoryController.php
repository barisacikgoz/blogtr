<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\Article;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('back.categories.index', compact('categories'));
    }

    public function switch(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->status = $request->statu == 'true' ? 1 : 0;
        $category->save();
    }

    public function getData(Request $request)
    {
        $category = Category::findOrFail($request->id);
        return response()->json($category);
    }

    public function create(Request $request)
    {
        $isExist = Category::whereSlug(Str::slug($request->category))->first();
        if ($isExist) {
            notify()->error('<b>' . $request->category . '</b>' . ' adında bir kategori var!');
            return redirect()->back();
        }
        $category = new Category();
        $category->name = $request->category;
        $category->slug = Str::slug($request->category);
        $category->save();
        notify()->success('Kategori Başarıyla Eklendi.');
        return redirect()->back();
    }

    public function update(Request $request)
    {

        $isSlug = Category::whereSlug(Str::slug($request->slug))->whereNotIn('id', [$request->id])->first();
        $isName = Category::whereName($request->category)->whereNotIn('id', [$request->id])->first();
        if ($isSlug or $isName) {
            notify()->error('<b>' . $request->category . '</b>' . ' adında bir kategori var!');
            return redirect()->back();
        }
        $category = Category::find($request->id);
        $category->name = $request->category;
        $category->slug = Str::slug($request->slug);
        $category->save();
        notify()->success('Kategori Başarıyla Güncellendi.');
        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $category = Category::findOrFail($request->id);
        if ($category->id == 1) {
            notify()->error('Bu Kategori Silinemez.');
            return redirect()->back();
        }
        $message='';
        $count = $category->articleCount();
        if (($count) > 0) {
            Article::where('category_id',$category->id)->update(['category_id'=>1]);
            $defaultCategory = Category::find(1);
            $message = 'Bu kategoriye ait ' . $count . ' makale ' . $defaultCategory->name . ' kategorisine taşındı.';
        }
        notify()->success($message , 'Kategori Başarıyla Silindi.');
        $category->delete();

        return redirect()->back();
    }
}
