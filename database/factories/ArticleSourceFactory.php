<?php

namespace Database\Factories;

use App\Models\ArticleSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArticleSource>
 */
class ArticleSourceFactory extends Factory
{
    protected $model = ArticleSource::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'url' => $this->faker->url,
        ];
    }
}
