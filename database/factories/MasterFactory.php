<?php

namespace Database\Factories;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

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
        $toHour = $fromHour + random_int(4, 8);

        if (!random_int(0, 2)) $fromHour .= ':30';
        if (!random_int(0, 2)) $toHour .= ':30';

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'worked_days' => $this->workedDays[random_int(0, count($this->workedDays) - 1)],
            'from_hour' => (new Carbon)->setTimeFromTimeString($fromHour),
            'to_hour' => (new Carbon)->setTimeFromTimeString($toHour),
        ];
    }
}
