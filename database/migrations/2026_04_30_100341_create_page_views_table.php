<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('url', 2048);
            $table->string('method', 10)->default('GET');
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referer', 2048)->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->timestamp('viewed_at')->useCurrent();
            $table->timestamps();

            $table->index('viewed_at');
            $table->index('url');
        });

        DB::unprepared("
            CREATE TRIGGER page_views_no_update
            BEFORE UPDATE ON `page_views`
            FOR EACH ROW
            BEGIN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Updates are not allowed on page_views';
            END
        ");

        DB::unprepared("
            CREATE TRIGGER page_views_no_delete
            BEFORE DELETE ON `page_views`
            FOR EACH ROW
            BEGIN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Deletes are not allowed on page_views';
            END
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
