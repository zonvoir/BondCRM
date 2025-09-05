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
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('icon_logo_dark')->nullable();
            $table->string('icon_logo_white')->nullable();
            $table->string('logo_dark')->nullable();
            $table->string('logo_white')->nullable();
            $table->string('favicon')->nullable();
            $table->string('company_name')->nullable();
            $table->string('allowed_file_types');
            $table->json('countries');
            $table->json('date_format')->nullable();
            $table->json('time_format')->nullable();
            $table->json('timezones');
            $table->string('app_name')->nullable();
            $table->string('app_description')->nullable();
            $table->string('app_logo')->nullable();
            $table->string('theme_color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
