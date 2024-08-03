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
        if ($this->file_id == 0 and isset(json_decode($this->images)->images->original))
            $image = ['path' => config('app.admin_site_url_file_old') . json_decode($this->images)->images->original, 'caption' => $this->title];
        elseif (isset($this->thumbnail->path) and isset($this->thumbnail->caption))
            $image = ['path' => $this->thumbnail->path ?? '', 'caption' => $this->thumbnail->caption ?? ''];
        else $image = ['path' => '', 'caption' => ''];


        return [
            'category' => ['title' => $this->category->title, 'slug' => $this->category->slug],
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->description,
            'image' => $image,
            'seo_title' => empty($this->seo_title) ? $this->title : $this->seo_title,
            'seo_description' => empty($this->seo_description) ? $this->description : $this->seo_description,
            'seo_follow' => $this->seo_follow,
            'seo_index' => $this->seo_index,
            'seo_canonical' => $this->seo_canonical,
            'author' => $this->author->name . ' ' . $this->author->family,
            'created_by' => $this->author->name,
            'created_at' => showDate($this->published_at),
        ];
    }
}


