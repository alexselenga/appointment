<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Master
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property mixed $worked_days
 * @property string $from_hour
 * @property string $to_hour
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\MasterFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Master newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Master newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Master query()
 * @method static \Illuminate\Database\Eloquent\Builder|Master whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Master whereFromHour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Master whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Master whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Master whereToHour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Master whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Master whereWorkedDays($value)
 * @mixin \Eloquent
 */
class Master extends Model
{
    use HasFactory;
}
