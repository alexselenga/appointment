<?php

use Carbon\CarbonInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('masters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);

            $table->set('worked_days', [
                CarbonInterface::SUNDAY,
                CarbonInterface::MONDAY,
                CarbonInterface::TUESDAY,
                CarbonInterface::WEDNESDAY,
                CarbonInterface::THURSDAY,
                CarbonInterface::FRIDAY,
                CarbonInterface::SATURDAY,
            ]);

            $table->float('from_hour');
            $table->float('to_hour');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('masters');
    }
};
