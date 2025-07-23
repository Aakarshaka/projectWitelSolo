<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUicApprovalsToSupportneededsTable extends Migration
{
    public function up()
    {
        Schema::table('supportneededs', function (Blueprint $table) {
            $table->json('uic_approvals')->nullable();
            // Ubah 'uic' jadi nama kolom sebelumnya jika berbeda
        });
    }

    public function down()
    {
        Schema::table('supportneededs', function (Blueprint $table) {
            $table->dropColumn('uic_approvals');
        });
    }
}
