<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->foreignUuid('activity_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->string('location')->nullable();
            $table->longText('description')->nullable();

            // Recurrence
            $table->string('recurrence', 20)->nullable();   // weekly, fortnightly, monthly
            $table->date('recurrence_ends_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('starts_at');
            $table->index('activity_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
