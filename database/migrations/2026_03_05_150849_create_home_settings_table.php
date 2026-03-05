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
        Schema::create('home_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('hero_is_featured')->default(true);
            $table->string('hero_title')->nullable();
            $table->string('hero_description')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('hero_button_about')->nullable();
            $table->string('hero_button_contact')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_settings');
    }
};
