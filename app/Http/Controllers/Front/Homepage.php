<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Mail;
use Validator;

// Models
use App\Models\Category;
use App\Models\Article;
use App\Models\Page;
use App\Models\Contact;
use App\Models\Config;

class Homepage extends Controller
{
    public function __construct()
    {
        if (Config::find(1)->active == 0){
            return redirect()->to('site-bakimda')->send();
        }
        view()->share('pages', Page::where('status', 1)->orderBy('order', 'ASC')->get());
        view()->share('categories', Category::where('status', 1)->inRandomOrder()->get());
    }

    public function index(){
        $data['articles']= Article::with('getCategory')->where('status', 1)->whereHas('getCategory', function ($query){
           $query->where('status', 1);
        })->orderBy('created_at', 'DESC')->paginate(10);
        $data['articles']->withPath(url('sayfa'));
        return view('front.homepage', $data);
    }

    public function single($category, $slug){
        $category = Category::whereSlug($category)->first() ?? abort(403, 'no such post found.');
        $article = Article::whereSlug($slug)->whereCategoryId($category->id)->first() ?? abort(403, 'no such post found.');

        $article->increment('hit');

        $data['article']=$article;
        return view('front.single', $data);
    }

    public function category($slug){
        $category = Category::whereSlug($slug)->first() ?? abort(403, 'no such category found.');
        $data['category'] = $category;
        $data['articles'] =Article::where('category_id', $category->id)->where('status', 1)->orderBy('created_at', 'DESC')->get();
        return view('front.category', $data);
    }

    public function page($slug){
        $page = Page::whereSlug($slug)->first() ?? abort(403, 'No such page found.');
        $data['page']=$page;
        return view('front.page', $data);
    }

    public function contact(){
        return view('front.contact');
    }


    public function contactpost(Request $request){

        $rules=[
            'name' => 'required|min:5',
            'email' => 'required|email',
            'number' => 'required',
            'message' => 'required'
        ];
        $validate = Validator::make($request->post(), $rules);

        if ($validate->fails()){
            return redirect()->route('contact')->withErrors($validate)->withInput();
        }

        Mail::send([],[], function ($message) use($request){
              $message->from('iletisim@blogsitesi.com','GrafikDesign_ea');
              $message->to('genel@blogsitesi.com');

              $message->setBody('Mesajı Gönderen : '.$request->name.'<br />
              Mesajı Gönderen Mail : '.$request->email.'<br />
              Mesaj Konusu : '.$request->topic.'<br />
              Mesaj : '.$request->message.'<br /><br />
              Mesaj Gönderilme Tarihi : '.now().'', 'text/html');

              $message->subject($request->name. ' İletişimden mesaj gönderdi.');
        });


//        ----DATABASE'E MAİL GÖNDERME----
//        $contact = new Contact;
//        $contact->name=$request->post('name');
//        $contact->email=$request->post('email');
//        $contact->number=$request->post('number');
//        $contact->message=$request->post('message');
//        $contact->save();

        return redirect()->route('contact')->with('success', 'sending is successful. Thank you');
    }

}
