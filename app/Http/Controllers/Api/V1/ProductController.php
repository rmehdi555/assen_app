<?php

namespace App\Http\Controllers\Api\V1;

use App\Classes\AxessoWebService;
use App\Classes\AxessoWebServiceDTO;
use App\Classes\Calculator;
use App\Http\Controllers\Controller;
use App\Models\CrawlerProduct;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function show($asin): JsonResponse
    {
        $product = CrawlerProduct::where('asin', $asin)->first();
        if (!filled($product) or $product->is_banned == 1 or $product->title==null)
            return $this->errorResponse(__('messages.item_not_found'), 404);
        $product->setHidden(['response']);
        $product->increment('view_count');
        $priceRial = Calculator::singleProduct(
            $product['price'],
            $product['weight_unit'],
            $product['exchange_type'],
            $product['region_type'],
            $product['weight']
        );
//        $priceRial['buyCost'] = showAmount($priceRial['buyCost']);
//        $priceRial['shipBroker'] = showAmount($priceRial['shipBroker']);
//        $priceRial['buyProfit'] = showAmount($priceRial['buyProfit']);
//        $priceRial['buyPrice'] = showAmount($priceRial['buyPrice']);
//        $priceRial['finalResult'] = showAmount($priceRial['finalResult']);
//        $priceRial['finalRialPrice'] = showAmount($priceRial['finalRialPrice']);
//        $priceRial['bBroker'] = showAmount($priceRial['bBroker']);
//        $priceRial['exchangeValue'] = showAmount((int)$priceRial['exchangeValue']);
        if ($product['ratings']==0){
            $product['ratings']='0';
        }
        if ($product['price_saving']==0){
            $product['price_saving']='0';
        }
        if ($product['retail_price']==0){
            $product['retail_price']='0';
        }
        return $this->successResponse([
            'data' => ['details' => $product, 'financial' => $priceRial],
        ], __('messages.item_found_success'));
    }

    public function refresh($asin): JsonResponse
    {
        $product = CrawlerProduct::where('asin', $asin)->first();
        if (!filled($product) or $product->is_banned == 1)
            return $this->errorResponse(__('messages.item_not_found'), 404);

        $response = json_decode((new AxessoWebService)->amazonProductInfo($product->url), true);
        if (isset($response[0]['logref']))
            return $this->errorResponse(__('messages.url_entered_invalid'));

        $data = AxessoWebServiceDTO::extractDetail($response, $product->url);
        $data['region'] = 1;

        if ($data['productRating']==0){
            $data['productRating']='0';
        }
        $product->update([
            'title' => $data['productTitle'],
            'price' => $data['price'],
            'retail_price' => $data['retailPrice'] ?? 0,
            'price_saving' => $data['priceSaving'] ?? 0,
            'ratings' => $data['productRating'],
            'images' => $data['imageUrlList'],
            'description' => $data['productDescription'],
            'reviews' => $data['reviews'],
            'videos' => $data['videoeUrlList'],
            'details' => $data['productDetails'],
            'features' => $data['features'],
            'exchange_type' => $data['exchangeType'],
            'exchange_name' => $data['exchangeName'],
            'region_type' => $data['region'],
            'region_name' => $data['regionName'],
            'weight' => $data['finalWeight'],
            'weight_unit' => $data['massUnit'],
            'categories' => $data['categories'],
            'variations' => $data['variations'],
            'response' => $response,
        ]);

        return $this->successResponse([
            'asin' => $asin,
        ], __('messages.item_refresh_success'));
    }
}
