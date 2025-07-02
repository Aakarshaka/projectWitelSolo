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
        Schema::create('tsels', function (Blueprint $table) {
            $table->id();
            $table->string('event');
            $table->string('unit');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('uic');
            $table->string('unit_collab')->nullable();
            $table->integer('complete');
            $table->string('status');
            $table->text('respond')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tsels');
    }
};
