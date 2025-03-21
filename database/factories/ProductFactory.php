<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_name' => $this->faker->realText(),
            'company_id' => $this->faker->numberBetween(1,3),
            'price' => $this->faker->numberBetween(1,1000),
            'stock' => $this->faker->numberBetween(1,1000),
            'comment' => $this->faker->realText(),
            'img_path' => null
        ];
    }
}
