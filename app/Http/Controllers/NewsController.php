<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::query()->latest('sort')->paginate();
        return $this->success($news);
    }

    public function show(News $news)
    {
        $news->increment('view_count');
        return $this->success($news);
    }
}
