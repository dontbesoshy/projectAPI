<?php

namespace App\Services\News\AD;

use App\Models\News\News;
use App\Resources\News\AD\NewsCollection;
use App\Services\BasicService;

class NewsService extends BasicService
{
    /**
     * Return news.
     *
     * @return NewsCollection
     */
    public function index(): NewsCollection
    {
        $news = News::all()->sortByDesc('id');

        return new NewsCollection($news);
    }
}
