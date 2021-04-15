<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Str;
use File;

class PageController extends Controller
{


    public function index(){
        $pages = Page::all();
        return view('back.pages.index', compact('pages'));
    }


    public function orders(Request $request){
        foreach ($request->get('page') as $key => $order){
            Page::where('id', $order)->update(['order' => $key]);
        }
    }

    public function switch(Request $request)
    {
        $page=Page::findOrFail($request->id);
        $page->status=$request->statu == "true" ? 1 : 0;
        $page->save();
    }

    public function create(){
        return view('back.pages.create');
    }


    public function post(Request $request){

        $last=Page::orderBy('order', 'DESC')->first();
        $request->validate([
            'title' => 'min:3',
            'image' => 'image|required|mimes:jpeg,png,jpg|max:2048'
        ]);

       $page = new Page;
       $page->title = $request->post('title');
       $page->content = $request->post('content');
       $page->order=$last->order+1;
       $page->slug = Str::slug($request->post('title'));

        if ($request->hasFile('image')) {
            $imageName = Str::slug($request->post('title')) . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads'), $imageName);
           $page->image = 'uploads/' . $imageName;
        }
       $page->save();
        toastr()->success('Sayfa Başarıyla oluşturuldu');
        return redirect()->route('admin.page.index');
    }


    public function updatePost(Request $request, $id)
    {
        $request->validate([
            'title' => 'min:3',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $page = Page::findOrFail($id);
        $page->title = $request->post('title');
        $page->content = $request->post('content');
        $page->slug = Str::slug($request->post('title'));

        if ($request->hasFile('image')) {
            $imageName = Str::slug($request->post('title')) . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads'), $imageName);
            $page->image = 'uploads/' . $imageName;
        }
        $page->save();
        toastr()->success('Sayfa Başarıyla Düzenlendi');
        return redirect()->route('admin.page.index');
    }


    public function update($id){
        $page=Page::findOrFail($id);
        return view('back.pages.update', compact('page'));
    }

    public function delete($id){
            $page = Page::find($id);
            if (File::exists($page->image)){
                File::delete(public_path($page->image));
            }
            $page->delete();
            toastr()->success('Sayfa Başarıyla silindi.');
            return redirect()->route('admin.page.index');
    }


}
