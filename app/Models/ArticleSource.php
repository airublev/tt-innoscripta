<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier_source',
        'name',
        'description',
        'url',
        'language',
        'country',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
