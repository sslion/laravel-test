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
        Schema::create('jokes', function (Blueprint $table) {
            $table->id();                          // Автоинкрементный ID
            $table->integer('joke_id')->unique();  // ID шутки из API
            $table->string('type');                // Тип шутки (programming/general и т.д.)
            $table->text('setup');                 // Начало шутки
            $table->text('punchline');             // Конец шутки (панчлайн)
            $table->timestamp('fetched_at');       // Когда получили шутку
            $table->timestamps();                  // created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jokes');
    }
};
