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
        Schema::table('newwarrooms', function (Blueprint $table) {
            // Tambahkan kolom supportneeded_id jika belum ada
            if (!Schema::hasColumn('newwarrooms', 'supportneeded_id')) {
                $table->unsignedBigInteger('supportneeded_id')->nullable()->after('id');
                $table->index('supportneeded_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newwarrooms', function (Blueprint $table) {
            if (Schema::hasColumn('newwarrooms', 'supportneeded_id')) {
                $table->dropIndex(['supportneeded_id']);
                $table->dropColumn('supportneeded_id');
            }
        });
    }
};