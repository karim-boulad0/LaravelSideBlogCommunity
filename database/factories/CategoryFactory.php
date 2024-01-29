<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class CategoryFactory extends Factory
{

    public function definition(): array
    {
        $content = $this->faker->word;
        $title = $this->faker->word;
        $imageNumber = $this->faker->numberBetween(1, 12);
        return [
            'content' => $content,
            'title' => $title,
            'image' => "http://127.0.0.1:8000/RequiredImages/{$imageNumber}.jpg",
        ];
    }
}
