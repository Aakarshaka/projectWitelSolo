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
        Schema::create('warrooms', function (Blueprint $table) {
        $table->id();
        $table->date('tgl')->nullable();
        $table->string('agenda')->nullable();
        $table->text('peserta')->nullable();
        $table->text('pembahasan')->nullable();
        $table->text('action_plan')->nullable();
        $table->text('support_needed')->nullable();
        $table->text('info_kompetitor')->nullable();
        $table->integer('jumlah_action_plan')->nullable();
        $table->text('update_action_plan')->nullable();
        $table->string('status_action_plan')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warrooms');
    }
};
