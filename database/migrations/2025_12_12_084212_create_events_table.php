<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('school_id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->text('description')->nullable();
            $table->string('attendance_token')->nullable();
            // $table->decimal('latitude', 10, 7)->nullable();
            // $table->decimal('longitude', 10, 7)->nullable();
            // $table->integer('radius')->nullable(); 
            $table->dateTime('attendance_start')->nullable();
            $table->dateTime('attendance_end')->nullable();
            $table->boolean('is_attendance_active')->default(false);

            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
