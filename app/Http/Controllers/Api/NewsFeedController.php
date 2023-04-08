<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\UserPreference;
use App\Providers\NewsServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsFeedController extends Controller
{
    protected $newsService;

    public function __construct(NewsServiceProvider $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = 10;

        $user = Auth::user();
        $preferences = UserPreference::where('user_id', $user->id)->get();
        $articles = Article::query();

        if (!$preferences->isEmpty()) {
            $categoryIds = $preferences->pluck('value')->toArray();

            $articles->whereIn('category_id', $categoryIds);
        }

        $searchTerm = $request->search;
        if (!empty($searchTerm)) {
            $articles->where(function ($query) use ($searchTerm) {
                $query->where('title', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('content', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $articleCollection = $articles->orderBy('created_at', 'desc')
            ->with('articleSource', 'category')
            ->paginate($perPage);

        return response()->json($articleCollection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
