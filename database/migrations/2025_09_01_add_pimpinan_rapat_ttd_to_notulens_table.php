<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notulens', function (Blueprint $table) {
            $table->string('pimpinan_rapat_ttd')->nullable()->after('isi_notulen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notulens', function (Blueprint $table) {
            $table->dropColumn('pimpinan_rapat_ttd');
        });
    }
};