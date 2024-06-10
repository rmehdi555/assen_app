<?php

namespace App\Http\Controllers\Api\V1;

use App\Classes\AxessoWebService;
use App\Classes\AxessoWebServiceDTO;
use App\Classes\Calculator;
use App\Enum\ProductDelivery;
use App\Helpers\Convertors;
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
use App\Models\ProductPriceLogs;
use App\Models\Products;
use App\Models\Sizes;
use App\Models\Standards;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

class ProductController extends Controller
{
    public function category($slug, ProductIndexRequest $request)
    {
        $category = ProductCategories::whereSlug($slug)->where('is_show', true)->first();
        if (!$category)
            return $this->errorResponse(__('messages.field_not_find'), 404);

        if (isset($request->factory_slug)) {
            $slugs = explode(',', $request->factory_slug);
            $factoryIds = Factories::whereIn('slug', $slugs)->where('is_show', true)->pluck('id');
        }

        if (isset($request->standard_slug)) {
            $slugs = explode(',', $request->standard_slug);
            $standardIds = Standards::whereIn('slug', $slugs)->where('is_show', true)->pluck('id');
        }


        if (isset($request->size_slug)) {
            $slugs = explode(',', $request->size_slug);
            $sizeIds = Sizes::whereIn('slug', $slugs)->where('is_show', true)->pluck('id');
        }


        $products = Products::select('products.*')->where('products.is_show', true)->with(['category', 'thumbnail'])
            ->join('factories', 'products.factory_id', 'factories.id')
            ->where('products.product_categories_id', $category->id)
            ->when(
                isset($factoryIds[0]),
                fn($q) => $q->whereIn('products.factory_id', $factoryIds)
            )
            ->when(
                isset($standardIds[0]),
                fn($q) => $q->where('products.standard_id', $standardIds)
            )
            ->when(
                isset($sizeIds[0]),
                fn($q) => $q->where('products.size_id', $sizeIds)
            )
            ->when(
                isset($request->q),
                fn($q) => $q->where('products.title', 'Like', '%' . $request->q . '%')
            )
            ->orderBy('factories.priority', 'desc')
            ->get();
//            ->paginate(isset($request->count) ?? config('custom.paginate_count'));

        $factories = Factories::where('is_show', true)->where('product_categories_id', $category->id)->orderBy('priority', 'desc')->get();
        $sizes = Sizes::where('is_show', true)->where('product_categories_id', $category->id)->orderBy('priority', 'desc')->get();
        $data = [];

        if (isset($request->sort_type) and $request->sort_type == 'size') {
            foreach ($products->groupBy('size_id') as $key => $value) {
                $size = Sizes::find($key);
                $data[] = [
                    'size_title' => 'قیمت' . ' ' . $size->title,
                    'size_slug' => $size->slug,
                    'products' => ProductIndexResource::collection($value)
                ];
            }
        } else {
            foreach ($products->groupBy('factory_id') as $key => $value) {
                $factory = Factories::find($key);
                $data[] = [
                    'factory_title' => 'قیمت' . ' ' . $factory->title,
                    'factory_slug' => $factory->slug,
                    'products' => ProductIndexResource::collection($value)
                ];
            }
        }

        $body = $category->body;
        $body = str_replace('src="../../../storage', 'src="' . config('app.admin_site_url_file'), $body);
        $body = str_replace('src="../../storage', 'src="' . config('app.admin_site_url_file'), $body);


        if ($category->file_id == 0 and isset(json_decode($category->images)->images->original))
            $image = ['path' => config('app.admin_site_url_file_old') . json_decode($category->images)->images->original, 'caption' => $category->title];
        elseif (isset($category->thumbnail->path) and isset($category->thumbnail->caption))
            $image = ['path' => $category->thumbnail->path ?? '', 'caption' => $category->thumbnail->caption ?? ''];
        else $image = ['path' => '', 'caption' => ''];

        return $this->successResponse([
            'image_path' => $image['path'],
            'image_caption' => $image['caption'],
            'title' => $category->title,
            'slug' => $category->slug,
            'body' => $body,
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


        if (isset($request->standard_slug)) {
            $slugs = explode(',', $request->standard_slug);
            $standardIds = Standards::whereIn('slug', $slugs)->where('is_show', true)->pluck('id');
        }


        if (isset($request->size_slug)) {
            $slugs = explode(',', $request->size_slug);
            $sizeIds = Sizes::whereIn('slug', $slugs)->where('is_show', true)->pluck('id');
        }


        $products = Products::where('is_show', true)->with(['category', 'thumbnail'])
            ->where('products.factory_id', $factory->id)
            ->when(
                isset($standardIds[0]),
                fn($q) => $q->where('products.standard_id', $standardIds)
            )
            ->when(
                isset($sizeIds[0]),
                fn($q) => $q->where('products.size_id', $sizeIds)
            )
            ->when(
                isset($request->q),
                fn($q) => $q->where('products.title', 'Like', '%' . $request->q . '%')
            )
            ->orderBy('priority', 'desc')
            ->get();
//            ->paginate(isset($request->count) ?? config('custom.paginate_count'));

        $factories = Factories::where('is_show', true)->where('product_categories_id', $factory->category->id)->orderBy('priority', 'desc')->get();
        $sizes = Sizes::where('is_show', true)->where('product_categories_id', $factory->category->id)->orderBy('priority', 'desc')->get();
        if ($factory->file_id == 0 and isset(json_decode($factory->images)->images->original))
            $image = ['path' => config('app.admin_site_url_file_old') . json_decode($factory->images)->images->original, 'caption' => $factory->title];
        elseif (isset($factory->thumbnail->path) and isset($factory->thumbnail->caption))
            $image = ['path' => $factory->thumbnail->path ?? '', 'caption' => $factory->thumbnail->caption ?? ''];
        else $image = ['path' => '', 'caption' => ''];


        $body = $factory->body;
        $body = str_replace('src="../../../storage', 'src="' . config('app.admin_site_url_file'), $body);
        $body = str_replace('src="../../storage', 'src="' . config('app.admin_site_url_file'), $body);


        return $this->successResponse([
            'image_path' => $image['path'],
            'image_caption' => $image['caption'],
            'title' => 'قیمت' . ' ' . $factory->title,
            'body' => $body,
            'seo_title' => $factory->seo_title,
            'seo_description' => $factory->seo_description,
            'seo_follow' => $factory->seo_follow,
            'seo_index' => $factory->seo_index,
            'seo_canonical' => $factory->seo_canonical,
            'category_title' => $factory->category->title,
            'category_slug' => $factory->category->slug,
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


        if (isset($request->factory_slug)) {
            $slugs = explode(',', $request->factory_slug);
            $factoryIds = Factories::whereIn('slug', $slugs)->where('is_show', true)->pluck('id');
        }

        if (isset($request->standard_slug)) {
            $slugs = explode(',', $request->standard_slug);
            $standardIds = Standards::whereIn('slug', $slugs)->where('is_show', true)->pluck('id');
        }


        $products = Products::where('is_show', true)->with(['category', 'thumbnail'])
            ->where('products.size_id', $size->id)
            ->when(
                isset($factoryIds[0]),
                fn($q) => $q->whereIn('products.factory_id', $factoryIds)
            )
            ->when(
                isset($standardIds[0]),
                fn($q) => $q->where('products.standard_id', $standardIds)
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
        if ($size->file_id == 0 and isset(json_decode($size->images)->images->original))
            $image = ['path' => config('app.admin_site_url_file_old') . json_decode($size->images)->images->original, 'caption' => $size->title];
        elseif (isset($size->thumbnail->path) and isset($size->thumbnail->caption))
            $image = ['path' => $size->thumbnail->path ?? '', 'caption' => $size->thumbnail->caption ?? ''];
        else $image = ['path' => '', 'caption' => ''];

        $body = $size->body;
        $body = str_replace('src="../../../storage', 'src="' . config('app.admin_site_url_file'), $body);
        $body = str_replace('src="../../storage', 'src="' . config('app.admin_site_url_file'), $body);


        return $this->successResponse([
            'image_path' => $image['path'],
            'image_caption' => $image['caption'],
            'title' => $size->title,
            'body' => $body,
            'seo_title' => $size->seo_title,
            'seo_description' => $size->seo_description,
            'seo_follow' => $size->seo_follow,
            'seo_index' => $size->seo_index,
            'seo_canonical' => $size->seo_canonical,
            'category_title' => $size->category->title,
            'category_slug' => $size->category->slug,
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

        $body = $product->body;
        $body = str_replace('src="../../../storage', 'src="' . config('app.admin_site_url_file'), $body);
        $body = str_replace('src="../../storage', 'src="' . config('app.admin_site_url_file'), $body);

        if ($product->file_id == 0 and isset(json_decode($product->images)->images->original))
            $image = ['path' => config('app.admin_site_url_file_old') . json_decode($product->images)->images->original, 'caption' => $product->title];
        elseif (isset($product->thumbnail->path) and isset($product->thumbnail->caption))
            $image = ['path' => $product->thumbnail->path ?? '', 'caption' => $product->thumbnail->caption ?? ''];
        else $image = ['path' => '', 'caption' => ''];

        $price = Convertors::changePrice($product->price, Config::get('custom.exchange_price'));

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
            'body' => $body,
            'seo_title' => $product->seo_title,
            'seo_description' => $product->seo_description,
            'seo_follow' => $product->seo_follow,
            'seo_index' => $product->seo_index,
            'seo_canonical' => $product->seo_canonical,
            'price' => $price,
            'fluctuation_pric' => $product->fluctuationPrice(),
            'place_of_delivery' => ProductDelivery::fromName($product->place_of_delivery)->value,
            'updated_at' => showDate($product->updated_at, 'Y/m/d'),
        ], __('messages.item_found_success'));
    }

    public function chart($slug)
    {
        $product = Products::whereSlug($slug)->where('is_show', true)->first();
        if (!$product)
            return $this->errorResponse(__('messages.field_not_find'), 404);

        $prices7 = ProductPriceLogs::where('product_id', $product->id)->whereDate('created_at', '>=', Carbon::now()->subDays(7))->orderBy('created_at')->get();
        $prices30 = ProductPriceLogs::where('product_id', $product->id)->whereDate('created_at', '>=', Carbon::now()->subDays(30))->orderBy('created_at')->get();
        $prices90 = ProductPriceLogs::where('product_id', $product->id)->whereDate('created_at', '>=', Carbon::now()->subDays(90))->orderBy('created_at')->get();
        $pricesAll = ProductPriceLogs::where('product_id', $product->id)->orderBy('created_at')->get();


        $results7 = $prices7->groupBy(function ($item, $key) {
            return showDate($item['created_at'], 'Y/m/d');
        });
        $results7 = $results7->map(function ($item, $key) {
            return $item[0]->price;
        });

        $results30 = $prices30->groupBy(function ($item, $key) {
            return showDate($item['created_at'], 'Y/m/d');
        });
        $results30 = $results30->map(function ($item, $key) {
            return $item[0]->price;
        });


        $results90 = $prices90->groupBy(function ($item, $key) {
            return showDate($item['created_at'], 'Y/m/d');
        });
        $results90 = $results90->map(function ($item, $key) {
            return $item[0]->price;
        });

        $resultsAll = $pricesAll->groupBy(function ($item, $key) {
            return showDate($item['created_at'], 'Y/m/d');
        });
        $resultsAll = $resultsAll->map(function ($item, $key) {
            return $item[0]->price;
        });


        return $this->successResponse([
            'prices_7_day' => [
                'x' => array_keys($results7->toArray()),
                'y' => array_values($results7->toArray())
            ],
            'prices_30_day' => [
                'x' => array_keys($results30->toArray()),
                'y' => array_values($results30->toArray())
            ],
            'prices_90_day' => [
                'x' => array_keys($results90->toArray()),
                'y' => array_values($results90->toArray())
            ],
            'prices_all_day' => [
                'x' => array_keys($resultsAll->toArray()),
                'y' => array_values($resultsAll->toArray())
            ],
        ], __('messages.item_found_success'));
    }
}
