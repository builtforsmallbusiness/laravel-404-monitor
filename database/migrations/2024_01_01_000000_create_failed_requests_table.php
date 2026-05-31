<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $table = config('404monitor.table_name', 'failed_requests');

        Schema::create($table, function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique();
            $table->string('user_agent', 500)->nullable();
            $table->string('referer', 500)->nullable();
            $table->string('source', 20)->default('direct')->index();
            $table->unsignedBigInteger('hit_count')->default(1);
            $table->timestamp('last_seen_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $table = config('404monitor.table_name', 'failed_requests');
        Schema::dropIfExists($table);
    }
};
