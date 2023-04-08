<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof \App\Models\Article) {
            return [
                'title' => $this->title,
                'description' => $this->content,
                'url' => $this->articleSource->url,
                'urlToImage' => null,
                'publishedAt' => $this->created_at,
                'category' => $this->category,
                'source' => [
                    'id' => $this->article_source_id,
                    'name' => $this->articleSource->name,
                    'description' => null,
                    'url' => null,
                    'language' => null,
                    'country' => null,
                ],
                'author' => $this->author,
                'content' => $this->content,
            ];
        } else {
            return [
                'title' => $this['title'],
                'description' => $this['description'],
                'url' => $this['url'],
                'urlToImage' => $this['urlToImage'],
                'publishedAt' => $this['publishedAt'],
                'category' => [
                    'name' => $this['source']['category']
                ],
                'source' => [
                    'id' => $this['source']['id'],
                    'name' => $this['source']['name'],
                    'description' => $this['source']['description'],
                    'url' => $this['source']['url'],
                    'language' => $this['source']['language'],
                    'country' => $this['source']['country'],
                ],
                'author' => $this['author'],
                'content' => $this['content'],
            ];
        }
    }
}
