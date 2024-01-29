<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{

    public function definition(): array
    {
        $imageNumber = $this->faker->numberBetween(1, 12);
        $userId = User::pluck('id')->all();

        return [
            'category_id' => function () {
                return Category::factory()->create()->id;
            },
            'title' => $this->faker->word,
            'content' => $this->faker->text,
            'smallDesc' => $this->faker->sentence,
            'tags' => $this->faker->word,
            'user_id' => $this->faker->randomElement($userId),
            'image' => "http://127.0.0.1:8000/RequiredImages/{$imageNumber}.jpg",
        ];
    }
}
