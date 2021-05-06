<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // static $number = 1;
        $is_marked_options = array(true, false);
        $is_marked_picked = $is_marked_options[mt_rand(0, 1)];

        return [
            'name' => $this->faker->words(3, true),
            // 'order' => $number++,
            'order' => 1,
            'original_name' => $this->faker->words(3, true) . '.jpeg',
            'is_marked' => $is_marked_picked,

        ];

    }
}
