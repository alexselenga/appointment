<?php

namespace Database\Factories;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master>
 */
class MasterFactory extends Factory
{
    protected $workedDays = [
        '0123456',
        '06',
        '12345',
        '135',
        '0246',
    ];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fromHour = random_int(7, 12);

        return [
            'name' => $this->faker->name,
            'worked_days' => $this->workedDays[random_int(0, count($this->workedDays) - 1)],
            'from_hour' => $fromHour,
            'to_hour' => $fromHour + random_int(4, 12),
        ];
    }
}
