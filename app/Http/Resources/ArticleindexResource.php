<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleindexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (!empty($this->images))
            $image = ['path' => config('app.admin_site_url_file_old') . json_decode($this->images)->images->original, 'caption' => $this->title];
        else
            $image = ['path' => config('app.admin_site_url_file') . $this->thumbnail->path, 'caption' => $this->thumbnail->caption];
        return [
            'category' => ['title' => $this->category->title, 'slug' => $this->category->slug],
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'is_show' => $this->is_show,
            'image' => $image,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_follow' => $this->seo_follow,
            'seo_index' => $this->seo_index,
            'seo_canonical' => $this->seo_canonical,
            'created_by' => $this->author->name,
            'created_at' => showDate($this->created_at),
        ];
    }
}


