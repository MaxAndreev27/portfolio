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
        Schema::table('home_settings', function (Blueprint $table) {
            //hero
            $table->json('hero_menu_item')->nullable()->after('hero_is_featured');
            $table->json('hero_title')->nullable()->change();
            $table->json('hero_description')->nullable()->change();
            $table->json('hero_button_about')->nullable()->change();
            $table->json('hero_button_contact')->nullable()->change();
            //about
            $table->json('about_menu_item')->nullable()->after('about_is_featured');
            $table->json('about_title')->nullable()->change();
            //projects
            $table->json('projects_menu_item')->nullable()->after('projects_is_featured');
            $table->json('projects_title')->nullable()->change();
            //technology
            $table->json('technology_menu_item')->nullable()->after('technology_is_featured');
            //contact
            $table->json('contact_menu_item')->nullable()->after('contact_is_featured');
            $table->json('contact_title')->nullable()->change();
            //footer
            $table->json('footer_copyright')->nullable()->change();
            $table->json('footer_powered')->nullable()->change();
            //SEO
            $table->json('seo_title')->nullable()->change();
            $table->json('seo_description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_settings', function (Blueprint $table) {
            $table->dropColumn('hero_menu_item');
            $table->dropColumn('about_menu_item');
            $table->dropColumn('projects_menu_item');
            $table->dropColumn('technology_menu_item');
            $table->dropColumn('contact_menu_item');
        });
    }
};
