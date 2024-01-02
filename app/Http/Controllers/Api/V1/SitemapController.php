<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\CrawlerProduct;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;


class SitemapController extends Controller
{
    public function index(): JsonResponse
    {
        $count=0;
        $time=Carbon::now()->format('Y-m-d');
        $data=[];
        $data[$count]['url']="https://charsooq.com";
        $data[$count]['lastModified']=$time;
        $data[$count]['changeFrequency']="always";
        $data[$count]['priority']=1;
        $count+=1;
        $data[$count]['url']="https://charsooq.com/calculator";
        $data[$count]['lastModified']=$time;
        $data[$count]['changeFrequency']="always";
        $data[$count]['priority']=0.9;
        $count+=1;
        $data[$count]['url']="https://charsooq.com/ebay";
        $data[$count]['lastModified']=$time;
        $data[$count]['changeFrequency']="always";
        $data[$count]['priority']=0.9;
        $count+=1;
        $data[$count]['url']="https://charsooq.com/turkey";
        $data[$count]['lastModified']=$time;
        $data[$count]['changeFrequency']="always";
        $data[$count]['priority']=0.9;
        $count+=1;
        $data[$count]['url']="https://charsooq.com/emirates";
        $data[$count]['lastModified']=$time;
        $data[$count]['changeFrequency']="always";
        $data[$count]['priority']=0.9;
        $count+=1;
        $data[$count]['url']="https://charsooq.com/china";
        $data[$count]['lastModified']=$time;
        $data[$count]['changeFrequency']="always";
        $data[$count]['priority']=0.9;
        $count+=1;
        $data[$count]['url']="https://charsooq.com/about-us";
        $data[$count]['lastModified']=$time;
        $data[$count]['changeFrequency']="always";
        $data[$count]['priority']=0.9;
        $count+=1;
        $data[$count]['url']="https://charsooq.com/contact-us";
        $data[$count]['lastModified']=$time;
        $data[$count]['changeFrequency']="always";
        $data[$count]['priority']=0.9;
        $count+=1;
        $data[$count]['url']="https://charsooq.com/faq";
        $data[$count]['lastModified']=$time;
        $data[$count]['changeFrequency']="always";
        $data[$count]['priority']=0.9;
        $count+=1;
        $data[$count]['url']="https://charsooq.com/ghavanincharsooq";
        $data[$count]['lastModified']=$time;
        $data[$count]['changeFrequency']="always";
        $data[$count]['priority']=0.9;
        $count+=1;
        $data[$count]['url']="https://charsooq.com/login";
        $data[$count]['lastModified']=$time;
        $data[$count]['changeFrequency']="always";
        $data[$count]['priority']=0.9;
        $count+=1;
        $data[$count]['url']="https://charsooq.com/other";
        $data[$count]['lastModified']=$time;
        $data[$count]['changeFrequency']="always";
        $data[$count]['priority']=0.9;
        $count+=1;
        $data[$count]['url']="https://charsooq.com/articles";
        $data[$count]['lastModified']=$time;
        $data[$count]['changeFrequency']="always";
        $data[$count]['priority']=0.9;
        $count+=1;
        $articles = Article::select('slug','category_id')->with(['category'])->get();
        foreach ($articles as $article){
            if($article->category->name=='slug'){
                $data[$count]['url']="https://charsooq.com/{$article->slug}";
                $data[$count]['lastModified']=$time;
                $data[$count]['changeFrequency']="always";
                $data[$count]['priority']=0.9;
                $count+=1;
            }
            else{
                $data[$count]['url']="https://charsooq.com/articles/{$article->slug}";
                $data[$count]['lastModified']=$time;
                $data[$count]['changeFrequency']="always";
                $data[$count]['priority']=0.9;
                $count+=1;
            }

        }
        $data[$count]['url']="https://charsooq.com/product";
        $data[$count]['lastModified']=$time;
        $data[$count]['changeFrequency']="always";
        $data[$count]['priority']=0.9;
        $count+=1;
        $products = CrawlerProduct::select('asin','title')->get();
        foreach ($products as $product){
            $slug=createSlug($product->title);
            $data[$count]['url']="https://charsooq.com/product/{$product->asin}/{$slug}";
            $data[$count]['lastModified']=$time;
            $data[$count]['changeFrequency']="always";
            $data[$count]['priority']=0.9;
            $count+=1;
        }
        return $this->successResponse($data, '');
    }
    public function robot(): JsonResponse
    {
        $data['rules']['userAgent']="*";
        $data['rules']['allow']= ["/",
            "/ebay",
            "/turkey",
            "/emirates",
            "/china",
            "/foreign-shopping",
            "/about-us",
            "/articles",
            "/articles/*",
            "/calculator",
            "/contact-us",
            "/faq",
            "/ghavanincharsooq",
            "/login",
            "/help",
            "/product/amz-*"];
        $data['rules']['disallow']= ["/dashboard",
            "/dashboard/*",
            "/checkout",
            "/checkout/*",
            "/invoice-callback-paypin-result",
            "/invoice-callback-paypin-result/*",
            "/invoice-callback-zarinpal",
            "/wallet-charge-callback-paypin-result",
            "/wallet-charge-callback-paypin-result/*",
            "/wallet-charge-callback-zarinpal",
            "/gp/*",
            "/_next/"];
        $data['sitemap']='https://charsooq.com/sitemap.xml';
        return $this->successResponse($data, '');
    }
}
