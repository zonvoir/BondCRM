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
        Schema::create('live_chat_settings', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('pusher_app_id')->nullable();
            $table->string('pusher_app_key')->nullable();
            $table->string('pusher_app_secret')->nullable();
            $table->string('pusher_host')->nullable();
            $table->string('pusher_port')->nullable();
            $table->string('pusher_scheme')->default('https');
            $table->string('pusher_app_cluster')->nullable();
            $table->string('ably_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_chat_settings');
    }
};
