<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleindexResource;
use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\SlidersResource;
use App\Models\Article;
use App\Models\Factories;
use App\Models\ProductCategories;
use App\Models\Products;
use App\Models\Sizes;
use App\Models\Sliders;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index(): JsonResponse
    {
        $sliders = Sliders::where('is_show', 1)->with(['thumbnail'])->orderBy('updated_at', 'desc')->orderBy('created_at', 'desc')->get();
        $sliders = SlidersResource::collection($sliders);
        $menus = ProductCategories::where('parent_id', 0)->where('is_show', true)->orderBy('priority', 'desc')->get(['id', 'title', 'slug']);
        foreach ($menus as $menu) {
            $menu['factories'] = Factories::where('product_categories_id', $menu->id)->where('is_show', true)->orderBy('priority', 'desc')->get(['title', 'slug']);
            $menu['sizes'] = Sizes::where('product_categories_id', $menu->id)->where('is_show', true)->orderBy('priority', 'desc')->get(['title', 'slug']);
        }
        $productTables = ProductCategories::where('parent_id', 0)->where('is_show', true)->orderBy('priority', 'desc')->get(['id', 'title', 'slug']);
        foreach ($productTables as $productTable) {
            $products = Products::where('product_categories_id', $productTable->id)->where('is_show', true)->orderBy('updated_at', 'desc')->limit(5)->get();
            $productTable['products'] = ProductIndexResource::collection($products);
        }
        $articles = Article::where('is_show', true)->with(['category', 'thumbnail', 'author'])->latest()
            ->limit(4)->get();
        return $this->successResponse([
            'menus' => $menus,
            'sliders' => $sliders,
            'productTables' => $productTables,
            'articles' => ArticleindexResource::collection($articles),
        ], '');
    }
}
