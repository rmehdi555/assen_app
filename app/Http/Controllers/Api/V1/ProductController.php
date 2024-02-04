<?php

namespace App\Http\Controllers\Api\V1;

use App\Classes\AxessoWebService;
use App\Classes\AxessoWebServiceDTO;
use App\Classes\Calculator;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Product\ProductIndexRequest;
use App\Http\Resources\FactoryIndexResource;
use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\SizeIndexResource;
use App\Models\Article;
use App\Models\CrawlerProduct;
use App\Models\Exchanges;
use App\Models\Factories;
use App\Models\ProductCategories;
use App\Models\Products;
use App\Models\Sizes;
use App\Models\Standards;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

class ProductController extends Controller
{
    public function category($slug, ProductIndexRequest $request)
    {
        $category = ProductCategories::whereSlug($slug)->where('is_show', true)->first();
        if (!$category)
            return $this->errorResponse(__('messages.field_not_find'), 404);

        if (isset($request->factory_slug))
            $factory = Factories::whereSlug($request->factory_slug)->where('is_show', true)->first();

        if (isset($request->standard_slug))
            $standard = Standards::whereSlug($request->standard_slug)->where('is_show', true)->first();

        if (isset($request->size_slug))
            $size = Sizes::whereSlug($request->size_slug)->where('is_show', true)->first();


        $products = Products::where('is_show', true)->with(['category', 'thumbnail'])
            ->where('products.product_categories_id', $category->id)
            ->when(
                isset($factory->id),
                fn($q) => $q->where('products.factory_id', $factory->id)
            )
            ->when(
                isset($standard->id),
                fn($q) => $q->where('products.standard_id', $standard->id)
            )
            ->when(
                isset($size->id),
                fn($q) => $q->where('products.size_id', $size->id)
            )
            ->when(
                isset($request->q),
                fn($q) => $q->where('products.title', 'Like', '%' . $request->q . '%')
            )
            ->orderBy('priority', 'desc')
            ->get();
//            ->paginate(isset($request->count) ?? config('custom.paginate_count'));

        $factories = Factories::where('is_show', true)->where('product_categories_id', $category->id)->orderBy('priority', 'desc')->get();
        $sizes = Sizes::where('is_show', true)->where('product_categories_id', $category->id)->orderBy('priority', 'desc')->get();
        $data = [];

        if (isset($request->sort_type) and $request->sort_type == 'size') {
            foreach ($products->groupBy('size_id') as $key => $value) {
                $factory = Sizes::find($key);
                $data[] = [
                    'size_title' => $factory->title,
                    'size_slug' => $factory->slug,
                    'products' => ProductIndexResource::collection($value)
                ];
            }
        } else {
            foreach ($products->groupBy('factory_id') as $key => $value) {
                $factory = Factories::find($key);
                $data[] = [
                    'factory_title' => $factory->title,
                    'factory_slug' => $factory->slug,
                    'products' => ProductIndexResource::collection($value)
                ];
            }
        }


        if (!empty($category->images))
            $image = ['path' => config('app.admin_site_url_file_old') . json_decode($category->images)->images->original, 'caption' => $category->title];
        else
            $image = ['path' => config('app.admin_site_url_file') . $category->thumbnail->path, 'caption' => $category->thumbnail->caption];
        return $this->successResponse([
            'image_path' => $image['path'],
            'image_caption' => $image['caption'],
            'title' => $category->title,
            'body' => $category->body,
            'seo_title' => $category->seo_title,
            'seo_description' => $category->seo_description,
            'seo_follow' => $category->seo_follow,
            'seo_index' => $category->seo_index,
            'seo_canonical' => $category->seo_canonical,
            'data' => $data,
            'factories' => FactoryIndexResource::collection($factories),
            'sizes' => SizeIndexResource::collection($sizes),
        ], __('messages.item_found_success'));


    }

    public function factory($slug, ProductIndexRequest $request)
    {
        $factory = Factories::whereSlug($slug)->where('is_show', true)->first();
        if (!$factory)
            return $this->errorResponse(__('messages.field_not_find'), 404);

        if (isset($request->standard_slug))
            $standard = Standards::whereSlug($request->standard_slug)->where('is_show', true)->first();

        if (isset($request->size_slug))
            $size = Sizes::whereSlug($request->size_slug)->where('is_show', true)->first();


        $products = Products::where('is_show', true)->with(['category', 'thumbnail'])
            ->where('products.factory_id', $factory->id)
            ->when(
                isset($standard->id),
                fn($q) => $q->where('products.standard_id', $standard->id)
            )
            ->when(
                isset($size->id),
                fn($q) => $q->where('products.size_id', $size->id)
            )
            ->when(
                isset($request->q),
                fn($q) => $q->where('products.title', 'Like', '%' . $request->q . '%')
            )
            ->latest()
            ->get();
//            ->paginate(isset($request->count) ?? config('custom.paginate_count'));

        $factories = Factories::where('is_show', true)->where('product_categories_id', $factory->category->id)->orderBy('priority', 'desc')->get();
        $sizes = Sizes::where('is_show', true)->where('product_categories_id', $factory->category->id)->orderBy('priority', 'desc')->get();
        if (!empty($factory->images))
            $image = ['path' => config('app.admin_site_url_file_old') . json_decode($factory->images)->images->original, 'caption' => $factory->title];
        else
            $image = ['path' => config('app.admin_site_url_file') . $factory->thumbnail->path, 'caption' => $factory->thumbnail->caption];
        return $this->successResponse([
            'image_path' => $image['path'],
            'image_caption' => $image['caption'],
            'title' => $factory->title,
            'body' => $factory->body,
            'seo_title' => $factory->seo_title,
            'seo_description' => $factory->seo_description,
            'seo_follow' => $factory->seo_follow,
            'seo_index' => $factory->seo_index,
            'seo_canonical' => $factory->seo_canonical,
            'data' => ProductIndexResource::collection($products),
            'factories' => FactoryIndexResource::collection($factories),
            'sizes' => SizeIndexResource::collection($sizes),
        ], __('messages.item_found_success'));


    }

    public function size($slug, ProductIndexRequest $request)
    {
        $size = Sizes::whereSlug($slug)->where('is_show', true)->first();
        if (!$size)
            return $this->errorResponse(__('messages.field_not_find'), 404);

        if (isset($request->factory_slug))
            $factory = Factories::whereSlug($request->factory_slug)->where('is_show', true)->first();

        if (isset($request->standard_slug))
            $standard = Standards::whereSlug($request->standard_slug)->where('is_show', true)->first();


        $products = Products::where('is_show', true)->with(['category', 'thumbnail'])
            ->where('products.size_id', $size->id)
            ->when(
                isset($standard->id),
                fn($q) => $q->where('products.standard_id', $standard->id)
            )
            ->when(
                isset($factory->id),
                fn($q) => $q->where('products.factory_id', $factory->id)
            )
            ->when(
                isset($request->q),
                fn($q) => $q->where('products.title', 'Like', '%' . $request->q . '%')
            )
            ->latest()
            ->get();
//            ->paginate(isset($request->count) ?? config('custom.paginate_count'));

        $factories = Factories::where('is_show', true)->where('product_categories_id', $size->category->id)->orderBy('priority', 'desc')->get();
        $sizes = Sizes::where('is_show', true)->where('product_categories_id', $size->category->id)->orderBy('priority', 'desc')->get();
        if (!empty($size->images))
            $image = ['path' => config('app.admin_site_url_file_old') . json_decode($size->images)->images->original, 'caption' => $size->title];
        else
            $image = ['path' => config('app.admin_site_url_file') . $size->thumbnail->path, 'caption' => $size->thumbnail->caption];
        return $this->successResponse([
            'image_path' => $image['path'],
            'image_caption' => $image['caption'],
            'title' => $size->title,
            'body' => $size->body,
            'seo_title' => $size->seo_title,
            'seo_description' => $size->seo_description,
            'seo_follow' => $size->seo_follow,
            'seo_index' => $size->seo_index,
            'seo_canonical' => $size->seo_canonical,
            'data' => ProductIndexResource::collection($products),
            'factories' => FactoryIndexResource::collection($factories),
            'sizes' => SizeIndexResource::collection($sizes),
        ], __('messages.item_found_success'));


    }


    public function show($slug)
    {
        $product = Products::whereSlug($slug)->where('is_show', true)->first();
        if (!$product)
            return $this->errorResponse(__('messages.field_not_find'), 404);

        if (!empty($product->images))
            $image = ['path' => config('app.admin_site_url_file_old') . json_decode($product->images)->images->original, 'caption' => $product->title];
        else
            $image = ['path' => config('app.admin_site_url_file') . $product->thumbnail->path, 'caption' => $product->thumbnail->caption];

        if (Config::get('custom.exchange_price') == 'IRR') {
            $price = $product->price;
        } elseif (Config::get('custom.exchange_price') == 'USD' and $product->price != 0) {
            $price = round($product->price / Exchanges::find(1)->value, 2);
        } elseif (Config::get('custom.exchange_price') == 'EUR' and $product->price != 0) {
            $price = round($product->price / Exchanges::find(2)->value, 2);
        } else
            $price = 0;

        return $this->successResponse([
            'id' => $product->id,
            'category_title' => $product->category->title,
            'category_slug' => $product->category->slug,
            'factory_title' => $product->factory->title,
            'factory_slug' => $product->factory->slug,
            'size_title' => $product->size->title,
            'size_slug' => $product->size->slug,
            'standard_title' => $product->standard->title,
            'standard_slug' => $product->standard->slug,
            'image_path' => $image['path'],
            'image_caption' => $image['caption'],
            'title' => $product->title,
            'body' => $product->body,
            'seo_title' => $product->seo_title,
            'seo_description' => $product->seo_description,
            'seo_follow' => $product->seo_follow,
            'seo_index' => $product->seo_index,
            'seo_canonical' => $product->seo_canonical,
            'price' => $price,
            'fluctuation_pric' => $product->fluctuationPrice(),
            'updated_at' => showDate($product->updated_at, 'Y/m/d'),
        ], __('messages.item_found_success'));
    }
}
