<?php

use App\Models\User;
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
        Schema::create('imaps', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('imap_server')->nullable();
            $table->string('imap_user_name')->nullable();
            $table->string('password')->nullable();
            $table->string('imap_port')->nullable();
            $table->string('folder')->nullable();
            $table->enum('imap_encryption', ['TLS', 'SSL'])->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaps');
    }
};
