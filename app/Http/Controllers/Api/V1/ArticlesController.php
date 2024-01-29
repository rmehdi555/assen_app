<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Articles\ArticlesCategoryRequest;
use App\Http\Requests\V1\Articles\ArticlesIndexRequest;
use App\Http\Resources\ArticleindexResource;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function index(ArticlesIndexRequest $request): JsonResponse
    {
        $articles = Article::where('is_show', true)->with(['category', 'thumbnail', 'author'])->latest()
            ->paginate(isset($request->count) ?? config('custom.paginate_count'));
        return $this->successResponse([
            'articles' => ArticleindexResource::collection($articles),
            'total' => $articles->total(),
            'perPage' => $articles->perPage(),
            'currentPage' => $articles->currentPage(),
            'lastPage' => $articles->lastPage(),
        ], '');
    }

    public function show($slug): JsonResponse
    {
        $article = Article::whereSlug($slug)->where('is_show', true)
            ->with(['category', 'thumbnail'])->first();
        if (!$article)
            return $this->errorResponse(__('messages.field_not_find'), 404);
        $article->increment('view_count');
        $data = new ArticleindexResource($article);
        return $this->successResponse($data, '');
    }


    public function future(ArticlesIndexRequest $request): JsonResponse
    {
        $articles = Article::where('is_future', 1)->where('is_show', true)->latest()
            ->paginate($request->count);
        $data = ArticleindexResource::collection($articles);
        return $this->successResponse($data, '');
    }

    public function mostView(ArticlesIndexRequest $request): JsonResponse
    {
        $articles = Article::orderByDesc('view_count')->with(['category', 'thumbnail', 'author'])->where('is_show', true)
            ->paginate($request->count);
        $data = ArticleindexResource::collection($articles);
        return $this->successResponse($data, '');
    }
}
