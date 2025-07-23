<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newwarroom', function (Blueprint $table) {
            $table->id();
            $table->date('tgl')->nullable();
            $table->string('agenda')->nullable();
            $table->string('uic')->nullable();   
            $table->text('peserta')->nullable();
            $table->text('pembahasan')->nullable();
            $table->text('support_needed')->nullable();
            $table->text('info_kompetitor')->nullable();
            $table->integer('jumlah_action_plan')->default(0); // sesuai alter
            $table->unsignedBigInteger('supportneeded_id')->nullable(); // sesuai alter (nanti bisa relasi FK)
            $table->timestamps();

            // Langsung tambahkan index (sesuai alter)
            $table->index('tgl');
            $table->index('agenda');
            $table->index('uic');
            $table->index('supportneeded_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newwarrooms');
    }
};
