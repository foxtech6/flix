<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('code', 6)
                ->unique();
            $table->foreignId('from_city_id')
                ->constrained('cities')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('to_city_id')
                ->constrained('cities')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->unsignedInteger('place_count');
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
        Schema::dropIfExists('trips');
    }
}
