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
        Schema::create('tiftas', function (Blueprint $table) {
            $table->id();
            $table->string('Event');
            $table->string('Unit/TELDA');
            $table->date('Start Date');
            $table->date('End Date');
            $table->string('Notes To Follow Up');
            $table->string('UIC');
            $table->string('Unit Collaborator');
            $table->integer('%Complete');
            $table->string('Status');
            $table->string('Respon UIC');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiftas');
    }
};
