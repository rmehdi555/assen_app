<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $body = $this->body;
        $body = str_replace('src="../../../storage', 'src="' . config('app.admin_site_url_file'), $body);
        $body = str_replace('src="../../storage', 'src="' . config('app.admin_site_url_file'), $body);
        $body = str_replace('href="../../../storage', 'href="' . config('app.admin_site_url_file'), $body);
        $body = str_replace('href="../../storage', 'href="' . config('app.admin_site_url_file'), $body);

        if ($this->file_id == 0 and isset(json_decode($this->images)->images->original))
            $image = ['path' => config('app.admin_site_url_file_old') . json_decode($this->images)->images->original, 'caption' => $this->title];
        elseif (isset($this->thumbnail->path) and isset($this->thumbnail->caption))
            $image = ['path' => $this->thumbnail->path ?? '', 'caption' => $this->thumbnail->caption ?? ''];
        else $image = ['path' => '', 'caption' => ''];

        return [
            'category' => ['title' => $this->category->title, 'slug' => $this->category->slug],
            'title' => $this->title,
            'excerpt' => $this->description,
            'slug' => $this->slug,
            'body' => $body,
            'image' => $image,
            'seo_title' => empty($this->seo_title) ? $this->title : $this->seo_title,
            'seo_description' => empty($this->seo_description) ? $this->description : $this->seo_description,
            'seo_follow' => $this->seo_follow,
            'seo_index' => $this->seo_index,
            'seo_canonical' => $this->seo_canonical,
            'author' => $this->author->name . ' ' . $this->author->family,
            'created_by' => $this->author->name,
            'created_at' => showDate($this->created_at),
            'comments' => Comment::where('is_show', true)
                ->where('type', 'article')
                ->where('type_slug', $this->slug)
                ->select('comment', 'rate')
                ->get()
                ->toArray(),
        ];
    }
}


