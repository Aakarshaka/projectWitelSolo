<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('action_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('newwarroom_id');
            $table->integer('plan_number'); 
            $table->text('action_plan');
            $table->text('update_action_plan')->nullable();
            $table->enum('status_action_plan', [
                'Open', 
                'Progress', 
                'Need Discuss', 
                'Eskalasi', 
                'Done'
            ])->default('Open');
            $table->timestamps();

            // ✅ Perbaikan di sini
            $table->foreign('newwarroom_id')
                  ->references('id')
                  ->on('newwarrooms') // harus sesuai dengan nama tabel aslinya
                  ->onDelete('cascade');

            $table->index(['newwarroom_id', 'plan_number']);
            $table->index('status_action_plan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_plans');
    }
};
