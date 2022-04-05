<?php

use App\Models\Master;
use App\Models\Service;

/**
 * @var Service $service
 * @var Master $master
 */
?>

<div class="container mt-sm-5 my-1 col-8">
    <form wire:submit.prevent="saveAppointment" class="flex flex-col">
        <div class="field">
            <label for="client-name" class="field-label">Ваше имя</label>
            <input type="text" wire:model="client_name" id="client-name" class="field-control @error('client_name') is-invalid @enderror">
        </div>
        @error('client_name')
        <div class="invalid-feedback">
            {{$message}}
        </div>
        @enderror

        <div class="field">
            <label for="phone" class="field-label">Ваш телефон</label>
            <input type="text" wire:model="phone" id="phone" class="field-control @error('phone') is-invalid @enderror">
        </div>
        @error('phone')
        <div class="invalid-feedback">
            {{$message}}
        </div>
        @enderror

        <div class="field">
            <label for="service" class="field-label">Услуга</label>
            <select wire:model="service_id" wire:change="refresh" id="service" class="field-control @error('service_id') is-invalid @enderror">
                <option selected value="">Выберите услугу</option>
                @foreach($services as $_service)
                    <option wire:key="service-{{$_service['id']}}" value="{{$_service['id']}}">{{$_service['name']}}</option>
                @endforeach
            </select>
        </div>
        @error('service_id')
        <div class="invalid-feedback">
            {{$message}}
        </div>
        @enderror
        @if($service)
            <div class="mt-1 text-gray-500 text-sm sm:text-right sm:max-w-md">
                {{$service['description']}}
                <br>
                {{$service['price']}} руб. за {{$service['duration']}} мин.
            </div>
        @endif

        <div class="field">
            <label for="master" class="field-label">Мастер</label>
            <select wire:model="master_id" wire:change="refresh" id="master" class="field-control @error('master_id') is-invalid @enderror">
                <option selected value="">Выберите мастера</option>
                @foreach($masters as $master)
                    <option wire:key="master-{{$master['id']}}" value="{{$master['id']}}">{{$master['name']}}</option>
                @endforeach
            </select>
        </div>
        @error('master_id')
        <div class="invalid-feedback">
            {{$message}}
        </div>
        @enderror

        <div class="flex mt-4">
            @foreach($days as $date => $dateStr)
                <?php $bgClass = $date == $currentDay ? 'bg-gray-200' : 'bg-gray-100'?>
                <div wire:click="$emit('selectDate', '{{$date}}')" class="{{$bgClass}} hover:bg-gray-300 m-1 p-1 cursor-default">
                    {{$dateStr}}
                </div>
            @endforeach
        </div>

        <div class="flex">
            @foreach($currentTimeLine as $time => $timeStr)
                <?php $bgClass = $time == $currentTime ? 'bg-gray-200' : 'bg-gray-100'?>
                <div wire:click="$emit('selectTime', '{{$time}}')" class="{{$bgClass}} hover:bg-gray-300 m-1 p-1 cursor-default">
                    {{$timeStr}}
                </div>
            @endforeach
        </div>

        <button type="submit" class="text-white font-bold bg-purple-700 hover:bg-purple-800 py-2 px-4 mt-4 rounded">Создать заявку</button>
    </form>
</div>

