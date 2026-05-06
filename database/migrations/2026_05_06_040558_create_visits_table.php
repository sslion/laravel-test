<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');        // IP посетителя
            $table->string('city')->nullable();   // Город
            $table->string('country')->nullable(); // Страна
            $table->string('device_type')->nullable(); // Тип устройства
            $table->string('browser')->nullable();   // Браузер
            $table->text('user_agent')->nullable();  // Полный User-Agent
            $table->string('page_url');           // URL страницы
            $table->timestamp('visited_at');      // Время посещения
            $table->timestamps();
            
            // Индекс для быстрой фильтрации по времени
            $table->index('visited_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};