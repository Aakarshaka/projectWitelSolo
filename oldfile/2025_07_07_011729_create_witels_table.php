<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('witels', function (Blueprint $table) {
            $table->id();

            // Relasi ke snunits
            $table->unsignedBigInteger('snunit_id')->nullable();
            $table->foreign('snunit_id')->references('id')->on('snunits')->onDelete('cascade');

            // Relasi ke snteldas
            $table->unsignedBigInteger('sntelda_id')->nullable();
            $table->foreign('sntelda_id')->references('id')->on('snteldas')->onDelete('cascade');

            // Relasi ke snams
            $table->unsignedBigInteger('snam_id')->nullable();
            $table->foreign('snam_id')->references('id')->on('snams')->onDelete('cascade');

            $table->string('event');
            $table->string('unit')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('uic')->nullable();
            $table->string('unit_collab')->nullable();
            $table->integer('complete')->nullable();
            $table->string('status')->nullable();
            $table->text('respond')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('witels');
    }
};
