<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\News; // for use of model class News
use Illuminate\Support\Facades\Storage;

use App\Models\History;
use Carbon\Carbon;

class NewsController extends Controller
{
    //
    public function add()
    {
        return view('admin.news.create');
    }
    // 以下を追記
    
    public function create(Request $request)
    {
        // dd($request);
        // Validation
        $this->validate($request, News::$rules);
        
        $news = new News;
        $form = $request->all();
        
        if (isset($form['image'])) {
            //var_dump($request->file('image'));
            $path = $request->file('image')->store('public/image');
            $news->image_path = basename($path);
        } else {
            $news->image_path = null;
        }
        
        // delete unnecessary items
        unset($form['_token']);
        unset($form['image']);
        
        // store all data in news table
        $news->fill($form);
        $news->save();
        
        // admin/news/createにリダイレクトする
        return redirect('admin/news');
    }
    
    public function index(Request $request) {
        
        $cond_title = $request->cond_title;
        //var_dump($request->cond_title);
        if ($cond_title != '') {
            $posts = News::where('title', $cond_title)->get();
        //var_dump($posts);
        } else {
            $posts = News::all();
        }
        
        //\Debugbar::info('デバッガーの情報ですよ');
        
        return view('admin.news.index', ['posts' => $posts, 'cond_title' => $cond_title]);
    }

    public function edit(Request $request) {
        $news = News::find($request->id);
        //var_dump($news);
        if (empty($news)) {
            abort(404);
        }
        return view('admin.news.edit', ['news_form' => $news]);
    }
    
    public function update(Request $request) {
        $this->validate($request, News::$rules);
        
        $news = News::find($request->id);
        
        $news_form = $request->all();

        if($request->remove == 'true') {
            //dd(Storage::Allfiles('public/image'));
            Storage::delete('public/image/' . $news->image_path);
            $news_form['image_path'] = null;
        } elseif ($request->file('image')) {
            var_dump($request->file('image'));
            $path = $request->file('image')->store('public/image');
            $news_form['image_path'] = basename($path);
        } else {
            $news_form['image_path'] = $news->image_path;
        }
        
        unset($news_form['image']);
        unset($news_form['remove']);
        unset($news_form['_token']);
        
        $news->fill($news_form)->save();
        
        $history = new History();
        $history->news_id = $news->id;
        $history->edited_at = Carbon::now();
        $history->save();
        
        return redirect('admin/news');
    }
    
    public function delete(Request $request) {
        $news = News::find($request->id);
        if (isset($news->image_path)) {
            Storage::delete('public/image/' . $news->image_path);
        }
        $news->delete();
        
        Return redirect('admin/news/');
        
    }
}
