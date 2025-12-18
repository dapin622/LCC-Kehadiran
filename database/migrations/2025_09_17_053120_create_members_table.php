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
         Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nisn')->unique();
            $table->enum('gender', ['Male', 'Female']);
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('class_rooms')->onDelete('set null');
            $table->string('photo')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
