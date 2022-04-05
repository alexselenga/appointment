<?php

namespace App\Http\Livewire;

use App\Models\Master;
use App\Models\Service;
use App\Models\Appointment as AppointmentModel;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Appointment extends Component
{
    public $client_name = null;
    public $phone = null;
    public $service_id = null;
    public $master_id = null;
    public $appointment_time = null;

    public $services = null;
    public $service = null;
    public $masters = [];
    public $master = null;
    public $days = [];
    public $currentDay = null;
    public $currentTimeLine = [];
    public $currentTime = [];

    protected $rules = [
        'client_name' => 'required',
        'phone' => 'required',
        'service_id' => 'required',
        'master_id' => 'required',
        'appointment_time' => 'required',
    ];

    protected $listeners = ['selectDate', 'selectTime'];

    public function refresh() {
//Инициализация
        $this->service = $this->services->find($this->service_id) ?? null;
        $this->masters = [];

        if (!$this->service) {
            $this->master_id = null;
            $this->appointment_time = null;
            $this->master = null;
            $this->days = [];
            $this->currentDay = null;
            $this->currentTimeLine = [];
            $this->currentTime = null;
            return;
        }

        $masters = Master::query()->orderBy('name')->get();
        $this->master = $masters->find($this->master_id) ?? null;

//Подготовка массива $timeLines (временных слотов) для каждого мастера с учетом его рабочих дней
        $timeLines = [];

        foreach ($masters as $master) {
            $timeLine = [];

            for ($addDays = 0; $addDays < AppointmentModel::NEAR_DAYS_COUNT; $addDays++) {
                $dateTime = Carbon::today()->addDays($addDays);
                if (!str_contains($master->worked_days, $dateTime->dayOfWeek)) continue;
                $dayTimeLine = $this->getDayTimeLine($master, $dateTime);
                $timeLine = array_merge($timeLine, $dayTimeLine);
            }

            $timeLines[$master->id] = $timeLine;
        }

//Коррекция $timeLines (временных слотов). Уже назначенные слоты = null.
        $appointments = AppointmentModel::with('service')->whereServiceId($this->service_id)->sevenDays()->get();

        foreach ($appointments as $appointment) {
            //Поиск первого временного слота
            $refTimeLine = &$timeLines[$appointment->master_id];
            $finishTime = Carbon::parse($appointment->appointment_time)->addMinutes($appointment->service->duration);
            $iTimeLine = array_search($appointment->appointment_time, $refTimeLine);
            if ($iTimeLine === false) continue;

            //Пометка временных слотов недействительными
            for (; $iTimeLine < count($refTimeLine); $iTimeLine++) {
                $time = Carbon::parse($refTimeLine[$iTimeLine]);
                if ($time->greaterThanOrEqualTo($finishTime)) break;
                $refTimeLine[$iTimeLine] = null;
            }
        }

//Коррекция $timeLines (временных слотов). Неподходящие временные слоты для текущей услуги = null.
        $durationSlotCount = (int)ceil($this->service->duration / 30);

        foreach ($timeLines as &$refTimeLine) {
            for ($iTime = 0; $iTime < count($refTimeLine); $iTime++) {
                if (!$refTimeLine[$iTime]) continue;

                $date = Carbon::parse($refTimeLine[$iTime])->toDateString();
                $allowTime = true;

                //Проверка временных слотов
                for ($iSlot = 0; $iSlot < $durationSlotCount; $iSlot++) {
                    if ($iTime + $iSlot < count($refTimeLine)
                        && $refTimeLine[$iTime + $iSlot]
                        && Carbon::parse($refTimeLine[$iTime + $iSlot])->toDateString() == $date
                    ) continue;

                    $allowTime = false;
                    break;
                }

                if ($allowTime) continue;

                //Пометка временных слотов недействительными
                for ($iSlot = 0; $iSlot < $durationSlotCount; $iSlot++) {
                    if ($iTime + $iSlot < count($refTimeLine)
                        && Carbon::parse($refTimeLine[$iTime + $iSlot])->toDateString() == $date
                    ) $refTimeLine[$iTime + $iSlot] = null;
                }
            }
        }

//Создание $this->masters. Только мастера со свободными временными слотами
        foreach ($timeLines as $master_id => $timeLine) {
            for ($iTime = 0; $iTime < count($timeLine); $iTime++) {
                if ($timeLine[$iTime]) {
                    $this->masters[] = $masters->find($master_id);
                    break;
                }
            }
        }

//Рабочие дни и временные слоты для выбранного мастера
        $this->days = [];
        $this->currentTimeLine = [];

        if ($this->master) {
            for ($addDays = 0; $addDays < AppointmentModel::NEAR_DAYS_COUNT; $addDays++) {
                $dateTime = Carbon::today()->addDays($addDays);
                if (!str_contains($this->master->worked_days, $dateTime->dayOfWeek)) continue;

                //Только дни со свободными временными слотами
                $dateTimeStr = $dateTime->toDateString();
                $timeLine = $timeLines[$this->master->id];

                for ($iTime = 0; $iTime < count($timeLine); $iTime++) {
                    if ($timeLine[$iTime] && Carbon::parse($timeLine[$iTime])->toDateString() == $dateTimeStr) {
                        $this->days[$dateTimeStr] = $dateTime->format('d.m.Y');
                        break;
                    }
                }
            }

            //Временные слоты для выбранного дня
            if ($this->currentDay) {
                foreach ($timeLines[$this->master->id] as $time) {
                    if (is_null($time)) continue;
                    $cTime = Carbon::parse($time);
                    if ($cTime->toDateString() != $this->currentDay) continue;
                    $this->currentTimeLine[$time] = $cTime->format('H:i');
                }
            }
        } else {
            $this->currentDay = null;
            $this->currentTime = null;
        }
    }

//Возвращает массив временных слотов для мастера на определенный день. Время дня тоже учитывается.
    protected function getDayTimeLine(Master $master, Carbon $dateTime) {
        $from_hour = Carbon::parse($master->from_hour);
        $fromDateTime = Carbon::create($dateTime->year, $dateTime->month, $dateTime->day, $from_hour->hour, $from_hour->minute);
        $to_hour = Carbon::parse($master->to_hour);
        $toDateTime = Carbon::create($dateTime->year, $dateTime->month, $dateTime->day, $to_hour->hour, $to_hour->minute);
        $now = Carbon::now();
        $dayTimeLine = [];

        for ($time = $fromDateTime; $time->lessThan($toDateTime); $time->addMinutes(30)) {
            if ($time->lessThanOrEqualTo($now)) continue;
            $dayTimeLine[] = $time->toDateTimeString();
        }

        return $dayTimeLine;
    }

    public function selectDate($date) {
        $this->currentDay = $date;
        $this->currentTime = null;
        $this->refresh();
    }

    public function selectTime($time) {
        $this->currentTime = $time;
        $this->refresh();
    }

    public function saveAppointment() {
        $this->appointment_time = $this->currentTime ?? null;

        AppointmentModel::create(
            $this->validate()
        );

        $this->refresh();
    }

    public function render()
    {
        $this->services = Service::query()->orderBy('name')->get();
        return view('livewire.appointment');
    }
}
