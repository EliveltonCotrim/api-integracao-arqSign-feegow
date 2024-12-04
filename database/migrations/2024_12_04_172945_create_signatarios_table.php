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
        Schema::create('signatarios', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('feegow_id_paciente')->nullable();
            $table->date('feegow_nascimento')->nullable();
            $table->char('feegow_cpf',11)->nullable();

            $table->foreignId('upload_files_history_id')->constrained();
            $table->integer('ordemAssinatura');
            $table->uuid('idSignatario');
            $table->string('nome');
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->boolean('pessoaFisica')->default(false);
            $table->boolean('pessoaJuridica')->default(false);
            $table->string('tipoAssinatura');
            $table->dateTime('dataAssinatura');
            $table->string('geoLocalizacao')->nullable();
            $table->string('ip')->nullable();
            $table->string('tipoDocumentoPessoaFisica')->nullable();
            $table->string('numeroDocumetoPessoaFisica')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signatarios');
    }
};
