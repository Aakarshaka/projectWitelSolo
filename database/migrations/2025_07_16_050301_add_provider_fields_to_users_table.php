<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'provider')) {
                $table->string('provider')->nullable();
            }
            if (!Schema::hasColumn('users', 'provider_id')) {
                $table->string('provider_id')->nullable();
            }

            $table->index(['provider', 'provider_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_provider_provider_id_index'); // ← sesuai nama index
            $table->dropColumn(['provider', 'provider_id', 'avatar']);
        });
    }
};
