<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\CrawlerProduct;
use App\Models\Factories;
use App\Models\ProductCategories;
use App\Models\Products;
use App\Models\Sizes;
use App\Models\Standards;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;


class SitemapController extends Controller
{
    public function index(): JsonResponse
    {
        $count = 0;
        $time = Carbon::now()->format('Y-m-d');
        $data = [];
        $data[$count]['url'] = "https://assen.ir";
        $data[$count]['lastModified'] = $time;
        $data[$count]['changeFrequency'] = "always";
        $data[$count]['priority'] = 1;
        $count += 1;
        $data[$count]['url'] = "https://assen.ir/about_us";
        $data[$count]['lastModified'] = $time;
        $data[$count]['changeFrequency'] = "always";
        $data[$count]['priority'] = 0.9;
        $count += 1;
        $data[$count]['url'] = "https://assen.ir/articles";
        $data[$count]['lastModified'] = $time;
        $data[$count]['changeFrequency'] = "always";
        $data[$count]['priority'] = 0.9;
        $count += 1;
        $data[$count]['url'] = "https://assen.ir/contact-us";
        $data[$count]['lastModified'] = $time;
        $data[$count]['changeFrequency'] = "always";
        $data[$count]['priority'] = 0.9;
        $count += 1;
        $productCategories = ProductCategories::select('slug')->get();
        foreach ($productCategories as $item) {
            $data[$count]['url'] = "https://assen.ir/category/{$item->slug}";
            $data[$count]['lastModified'] = $time;
            $data[$count]['changeFrequency'] = "always";
            $data[$count]['priority'] = 0.9;
            $count += 1;
        }
        $factories = Factories::select('slug')->get();
        foreach ($factories as $item) {
            $data[$count]['url'] = "https://assen.ir/factory/{$item->slug}";
            $data[$count]['lastModified'] = $time;
            $data[$count]['changeFrequency'] = "always";
            $data[$count]['priority'] = 0.9;
            $count += 1;
        }

        $articles = Article::select('slug')->get();
        foreach ($articles as $article) {
            $data[$count]['url'] = "https://assen.ir/articles/{$article->slug}";
            $data[$count]['lastModified'] = $time;
            $data[$count]['changeFrequency'] = "always";
            $data[$count]['priority'] = 0.9;
            $count += 1;

        }
        $products = Products::select('slug')->get();
        foreach ($products as $item) {
            $data[$count]['url'] = "https://assen.ir/product/{$item->slug}";
            $data[$count]['lastModified'] = $time;
            $data[$count]['changeFrequency'] = "always";
            $data[$count]['priority'] = 0.9;
            $count += 1;
        }

        $sizes = Sizes::select('slug')->get();
        foreach ($sizes as $item) {
            $data[$count]['url'] = "https://assen.ir/size/{$item->slug}";
            $data[$count]['lastModified'] = $time;
            $data[$count]['changeFrequency'] = "always";
            $data[$count]['priority'] = 0.9;
            $count += 1;
        }
        return $this->successResponse($data, '');

    }

    public function robot(): JsonResponse
    {
        $data['rules']['userAgent'] = "*";
        $data['rules']['allow'] = [
            "/",
        ];
        $data['rules']['disallow'] = [
            "/_next/"
        ];
        $data['sitemap'] = 'https://assen.ir/sitemap.xml';
        return $this->successResponse($data, '');
    }
}
