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
        Schema::create('supportneededs', function (Blueprint $table) {
            $table->id();
            $table->string('agenda');
            $table->string('unit_or_telda')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('off_day')->nullable();
            $table->text('notes_to_follow_up')->nullable();
            $table->string('uic')->nullable();
            $table->string('progress')->nullable(); // e.g. On Progress / Done
            $table->integer('complete')->nullable(); // 0-100 (%)
            $table->string('status')->nullable(); // e.g. Eskalasi / Done
            $table->text('response_uic')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supportneededs');
    }
};
