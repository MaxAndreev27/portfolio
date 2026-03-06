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
            //hero
            $table->boolean('hero_is_featured')->default(true);
            $table->string('hero_title')->nullable();
            $table->string('hero_description')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('hero_button_about')->nullable();
            $table->string('hero_button_contact')->nullable();
            //about
            $table->boolean('about_is_featured')->default(true);
            $table->string('about_title')->nullable();
            $table->json('about_timeline')->nullable();
            $table->json('about_skills')->nullable();
            //projects
            $table->boolean('projects_is_featured')->default(true);
            $table->string('projects_title')->nullable();
            //technology
            $table->boolean('technology_is_featured')->default(true);
            //contact
            $table->boolean('contact_is_featured')->default(true);
            $table->string('contact_title')->nullable();
            //footer
            $table->json('footer_social_links')->nullable();
            $table->string('footer_copyright')->nullable();
            $table->string('footer_powered')->nullable();
            //SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();

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
