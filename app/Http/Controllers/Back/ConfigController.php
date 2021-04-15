<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config;
use Illuminate\Support\Str;

class ConfigController extends Controller
{
    public function index(){
        $config=Config::find(1);
        return view('back.config.index', compact('config'));
    }

    public function update(Request $request){
        $config=Config::find(1);
        $config->title=$request->post('title');
        $config->active=$request->post('active');
        $config->facebook=$request->post('facebook');
        $config->twitter=$request->post('twitter');
        $config->linkedin=$request->post('linkedin');
        $config->github=$request->post('github');
        $config->youtube=$request->post('youtube');
        $config->instagram=$request->post('instagram');

        if ($request->hasFile('logo')){
         $logo=Str::Slug($request->post('title')). '-logo.' . $request->logo->getClientOriginalExtension();
         $request->logo->move(public_path('uploads'),$logo);
         $config->logo='uploads/'.$logo;
        }

        if ($request->hasFile('favicon')){
            $favicon=Str::Slug($request->post('title')). '-favicon.' . $request->favicon->getClientOriginalExtension();
            $request->favicon->move(public_path('uploads'), $favicon);
            $config->favicon='uploads/'.$favicon;

        }
            $config->save();
            toastr()->success('Ayarlar Başarıyla Güncellendi');
            return redirect()->back();
    }
}
