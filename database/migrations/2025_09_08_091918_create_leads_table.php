<?php

use App\Models\Country;
use App\Models\Setup\Sources;
use App\Models\Setup\Status;
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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Sources::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Status::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('address')->nullable();
            $table->string('position')->nullable();
            $table->string('city')->nullable();
            $table->string('email')->nullable();
            $table->string('state')->nullable();
            $table->string('website')->nullable();
            $table->foreignIdFor(Country::class)->nullable()->constrained()->cascadeOnDelete();
            $table->integer('phone')->nullable();
            $table->integer('zip_code')->nullable();
            $table->decimal('lead_value', 15, 2)->nullable();
            $table->string('company')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('date_contacted')->nullable();
            $table->boolean('public')->nullable();
            $table->boolean('is_date_contacted')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
