<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ArticleRepository
{
    /**
     * Get filtered articles based on search and category query parameters.
     *
     * @param Request $request
     * @return Builder
     */
    public function getFilteredArticles(Request $request): Builder
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $query = Article::query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($category) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('id', 'like', "%{$category}%");
            });
        }

        return $query;
    }
}

