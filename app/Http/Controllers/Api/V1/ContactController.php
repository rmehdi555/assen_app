<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Contact\ContactRequest;
use App\Http\Requests\V1\News\NewsRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
        public function index(ContactRequest $request): JsonResponse
    {
        $news = Contact::create([
            "email" => $request->email,
            "name" => $request->name,
            "cell_number" => $request->cell_number,
            "description" => $request->description,

        ]);
        return $this->successResponse($news->id, __('messages.contact_saved_successfully'));

    }
}
