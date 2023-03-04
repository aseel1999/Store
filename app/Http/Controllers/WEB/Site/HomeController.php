<?php

namespace App\Http\Controllers\WEB\Site;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Video;
use App\Models\Infographic;
use App\Models\LandingPage;
use App\Models\Language;
use App\Models\ArticleCategory;
use App\Models\Product;
use App\Models\Page;
use App\Models\Project;

use App\Models\Setting;
use App\Models\Opinion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->locales = Language::all();
        $this->settings = Setting::query()->first();
        view()->share([
            'locales' => $this->locales,
            'settings' => $this->settings,

        ]);
    }


    public function index()
    {
       $categories=Category::get();
       $products=Product::get();
       //$poosts=Article::where('category_id',$categories->id)->take(5)->get();
      
        $category1=Category::where('id','1')->first();
        $category2=Category::where('id','2')->first();
        $category3=Category::where('id','3')->first();
       
       
        
        
        return view('website.home',[
            'categories'=>$categories,
           
        
            'category1'=>$category1,
            'category2'=>$category2,
            'category3'=>$category3,
           'products'=>$products,
            
            
        ]);
        
    }
    public function contact()
    {
        return view('website.contact.index');
    }
    public function contactUs(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'phone'=>'required',
            'title' => 'required',
            'message' => 'required',

        ]);
        $contact = new Contact();
        $contact->name = $request->input('name');
        $contact->email = $request->input('email');
        $contact->phone = $request->input('phone');
        $contact->title = $request->input('title');
        $contact->message = $request->input('message');

        $contact->save();
        return redirect()->back();

}
public function privacy(){
    return view('website.privacy.index');
}
public function terms(){
    return view('website.term.index');
}
public function notfound(){
    return view('errors.404');
}
public function opinions(){
    //$opinions=Opinion::get();
    return view('website.opinion.opinions',[
             //'opinions'=>$opinions,

             ]);
   }
   public function opinionDetail($id){
    //$opinion=Opinion::where('id',$id)->first();
    $articles=Article::orderBy('id', 'desc')->take(6)->get();
    return view('website.opinion.opinion-details',[
      //  'opinion'=>$opinion,
        'articles'=>$articles,
    ]);
   }
   public function infographic(){
   // $infographics=Infographic::orderBy('id','desc')->take(12)->get();
    return view ('website.infographic',[
        //'infographics'=>$infographics,
    ]);
   }
   public function about(){
    return view('website.about');
   }
   public function locale(){
    $articles=Article::where('category_id','1')->orderBy('id','desc')->take(15)->get();
    return view('website.locale',[
        'articles'=>$articles
    ]);
   }
   
   public function media(){
   //$videoes=Video::orderBy('id','desc')->take(12)->get();
    return view ('website.videoes',[
       // 'videoes'=>$videoes,
    ]);
   }
   public function sports($id){
   // $sport=Sport::where('id',$id)->first();
    //$articles=Article::where('sport_id',$sport->id)->orderBy('id','desc')->take(12)->get();
    return view('website.sport',[
       // 'sport'=>$sport,
        //'articles'=>$articles,
    ]);
   }
   public function categoryArticle(){
    //  $category=Category::where('id','2')->orWhere('id','3')->first();
    $articles=Article::where('category_id',2)->orWhere('category_id',3)->orderBy('id','desc')->take(12)->get();
    return view ('website.globale',[
        'articles'=>$articles,
    ]);
   }
   public function search(Request $request){
    
    $keyword = $request->query('q');
    
    $news = Article::whereTranslationLike('title',  '%'.$keyword.'%')
        ->orWhereTranslationLike('detail', '%'.$keyword.'%' )
        ->orWhereTranslationLike('subtitle', '%'.$keyword.'%')
        ->orderBy('id', 'desc')
        ->paginate($this->settings->paginateTotal);
          
     
    return view('website.search',[
        'keyword'=>$keyword,
        'news'=>$news,
    ] );
   }

}