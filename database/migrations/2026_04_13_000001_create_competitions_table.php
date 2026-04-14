<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->text('content')->nullable();
            $table->uuid('thumbnail_id')->nullable();
            $table->foreign('thumbnail_id')->references('id')->on('media')->nullOnDelete();
            $table->enum('status', ['open', 'closed', 'results'])->default('open');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
