<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereAppointmentTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereClientName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Appointment extends Model
{
    use HasFactory;

    protected $fillable = ['client_name', 'phone', 'master_id', 'service_id', 'appointment_time'];

    public function master() {
        return $this->belongsTo(Master::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }
}
