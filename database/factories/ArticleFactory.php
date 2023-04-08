<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Category;
use App\Models\ArticleSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->sentence,
            'description' => $this->faker->paragraphs(3, true),
            'content' => $this->faker->text,
            'url' => $this->faker->url,
            'url_to_image' => $this->faker->imageUrl(),
            'published_at' => $this->faker->dateTimeThisMonth,
            'category_id' => Category::factory(),
            'article_source_id' => ArticleSource::factory(),
        ];
    }
}
