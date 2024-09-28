<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Comment\CommentRequest;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    public function index(CommentRequest $request): JsonResponse
    {
        $comment = Comment::create([
            "comment" => $request->comment,
            "phone" => $request->phone,
            "rate" => $request->rate ?? 5,
            "type" => $request->type ?? 'article',
            "type_slug" => $request->type_slug,
        ]);
        return $this->successResponse($comment->id, __('messages.comment_saved_successfully'));
    }
}
