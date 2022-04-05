<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Appointment
 *
 * @property int $id
 * @property string $client_name
 * @property string $phone
 * @property int $master_id
 * @property int $service_id
 * @property string $appointment_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Master $master
 * @property-read \App\Models\Service $service
 * @method static \Database\Factories\AppointmentFactory factory(...$parameters)
 * @method static Builder|Appointment newModelQuery()
 * @method static Builder|Appointment newQuery()
 * @method static Builder|Appointment query()
 * @method static Builder|Appointment whereAppointmentTime($value)
 * @method static Builder|Appointment whereClientName($value)
 * @method static Builder|Appointment whereCreatedAt($value)
 * @method static Builder|Appointment whereId($value)
 * @method static Builder|Appointment whereMasterId($value)
 * @method static Builder|Appointment wherePhone($value)
 * @method static Builder|Appointment whereServiceId($value)
 * @method static Builder|Appointment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Appointment extends Model
{
    use HasFactory;

    const NEAR_DAYS_COUNT = 7;

    protected $fillable = ['client_name', 'phone', 'master_id', 'service_id', 'appointment_time'];

    public function master() {
        return $this->belongsTo(Master::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }

    /**
     * Scope a query to only include the first 7 days records by appointment time
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeSevenDays(Builder $query)
    {
        $fromDate = Carbon::today();
        $toDate = Carbon::today()->addDays(static::NEAR_DAYS_COUNT - 1);

        return $query
            ->whereDate('appointment_time', '>=', $fromDate->toDateString())
            ->whereDate('appointment_time', '<=', $toDate->toDateString());
    }
}
