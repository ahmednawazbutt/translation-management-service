<?php

namespace Database\Factories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->word,
            'content' => $this->faker->sentence,
            'locale' => $this->faker->randomElement(['en', 'fr', 'es']),
            'tag' => $this->faker->randomElement(['mobile', 'desktop', 'web']),
        ];
    }
}
