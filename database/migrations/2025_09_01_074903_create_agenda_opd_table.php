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
        Schema::create('agenda_opd', function (Blueprint $table) {
            $table->id();
            $table->string('agenda_id');
            $table->unsignedBigInteger('opd_id');
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('agenda_id')->references('agenda_id')->on('agendas')->onDelete('cascade');
            $table->foreign('opd_id')->references('opd_id')->on('opd')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate entries
            $table->unique(['agenda_id', 'opd_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_opd');
    }
};
