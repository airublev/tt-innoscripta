<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'url',
        'url_to_image',
        'author',
        'content',
        'source',
        'category_id',
        'article_source_id',
        'published_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function articleSource()
    {
        return $this->belongsTo(ArticleSource::class);
    }
}
