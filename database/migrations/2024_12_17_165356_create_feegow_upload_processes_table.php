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
        Schema::create('feegow_upload_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upload_files_history_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('paciente_id');
            $table->string('cpf', 11);
            $table->string('nascimento')->nullable();
            $table->string('arquivo_descricao')->nullable();
            $table->tinyInteger('upload_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feegow_upload_processes');
    }

};
