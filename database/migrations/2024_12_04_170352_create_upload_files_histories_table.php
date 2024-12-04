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
        Schema::create('upload_files_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('arqSign_idProcesso');
            $table->uuid('arqSign_idConta');
            $table->uuid('arqSign_idWebhook');
            $table->timestamp('arqSign_dataHoraAtualProcesso');
            $table->string('arqSign_nomeProcesso');
            $table->uuid('arqSign_idPasta');
            $table->string('arqSign_caminhoDaPasta');
            $table->uuid('arqSign_idResponsavel');
            $table->string('arqSign_NomeResponsavel');
            $table->string('arqSign_status');
            $table->dateTime('arqSign_dataConclusao');
            $table->string('statusProcesso')->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_files_histories');
    }
};
