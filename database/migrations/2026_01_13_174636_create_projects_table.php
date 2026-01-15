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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Основна інформація
            $table->string('title'); // Назва проекту
            $table->string('slug')->unique(); // URL-адреса (наприклад, 'my-e-commerce-site')
            $table->text('description'); // Детальний опис
            $table->string('excerpt')->nullable(); // Короткий опис для прев'ю (карток)

            // Медіа та посилання
            $table->string('image')->nullable(); // Шлях до головного скріншоту
            $table->string('url')->nullable(); // Посилання на live-demo
            $table->string('github_url')->nullable(); // Посилання на репозиторій

            // Додаткові дані
            $table->date('completed_at')->nullable(); // Дата завершення (для сортування за новизною)
            $table->boolean('is_featured')->default(false); // Чи виводити проект у топ (обране)
            $table->integer('order')->default(0); // Для ручного сортування проектів

            // Статус проекту
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
