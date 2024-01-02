<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SlidersResource;
use App\Models\Invoice;
use App\Models\Sliders;
use App\Models\User;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SlidersController extends Controller
{
    public function index():JsonResponse
    {
        $sliders = Sliders::where('is_show', 1)->with(['thumbnail'])->orderBy('updated_at', 'desc')->orderBy('created_at', 'desc')->get();
        $sliders = SlidersResource::collection($sliders);
        $users=User::count();
        $invoices=Invoice::count()+39000;
        $invoices=floor($invoices/ 500) * 500 ;
        $date='2017-10-23';
        $currentDate = new DateTime();
        $providedDate = new DateTime($date);
        $interval = $providedDate->diff($currentDate);
        $years = $interval->y;
        $months = $interval->m;
        $days = $interval->d;
        return $this->successResponse(['sliders'=>$sliders,'advantage'=>['users'=>$users, 'invoices'=>$invoices,
            'createdDate'=>['years'=>$years,'months'=>$months,'days'=>$days]]], '');
    }

}
