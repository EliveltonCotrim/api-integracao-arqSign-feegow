<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArqSignWebhookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->IdConta == config('arqsign.keys.id_conta') && $this->IdWebhook == config('arqsign.keys.id_webhook');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "Evento" => ['string', 'in:ProcessoConcluido'],
            "IdProcesso" => ['required', 'uuid'],
            "IdConta" => ['required', 'uuid'],
            "IdWebhook" => ['required', 'uuid'],
            "NomeProcesso" => ['required', 'string'],
            "IdPasta" => ['nullable', 'uuid'],
            "CaminhoDaPasta" => ['nullable', 'string'],
            "IdResponsavel" => ['nullable', 'uuid'],
            "NomeResponsavel" => ['nullable', 'string'],
            "Status" => ['nullable', 'string'],
            "DataCadastro" => ['nullable', 'date'],
            "DataHoraAtual" => ['nullable', 'date'],
            "DataEnvio" => ['nullable', 'date'],
            "DataConclusao" => ['nullable', 'date'],
            "DataReenvio" => ['nullable', 'date'],
            "DataExpiracao" => ['nullable', 'date'],
            "ExpiracaoDias" => ['nullable', 'integer'],
            "Signatarios" => ['required', 'array'],
            "Documentos" => ['required', 'array'],

            "Signatarios.*.OrdemAssinatura" => ['required', 'integer'],
            "Signatarios.*.Id" => ['required', 'uuid'],
            "Signatarios.*.Nome" => ['required', 'string'],
            "Signatarios.*.Email" => ['nullable', 'email'],
            "Signatarios.*.Telefone" => ['nullable', 'string'],

            "Signatarios.*.PapelSignatario.PessoaFisica" => ['nullable', 'array'],
            "Signatarios.*.PapelSignatario.PessoaFisica.*" => ['nullable', 'string'],
            "Signatarios.*.PapelSignatario.PessoaJuridica" => ['nullable', 'array'],
            "Signatarios.*.PapelSignatario.PessoaJuridica.*" => ['nullable', 'string'],
            "Signatarios.*.AssinaturaRecusada" => ['nullable', 'string', 'max:10'],
            "Signatarios.*.MotivoRecusa" => ['nullable', 'string'],
            "Signatarios.*.DadosAssinatura.TipoAssinatura" => ['nullable', 'string'],
            "Signatarios.*.DadosAssinatura.DataAssinatura" => ['nullable', 'date'],
            "Signatarios.*.DadosAssinatura.Ip" => ['nullable', 'ip'],
            "Signatarios.*.DadosAssinatura.GeoLocalizacao" => ['nullable', 'string'],
            "Signatarios.*.DadosAssinatura.DadosCertificado.Nome" => ['nullable', 'string'],
            "Signatarios.*.DadosAssinatura.DadosCertificado.Emissor" => ['nullable', 'string'],
            "Signatarios.*.DadosAssinatura.DadosCertificado.ValidadeInicio" => ['nullable', 'date'],
            "Signatarios.*.DadosAssinatura.DadosCertificado.ValidadeFim" => ['nullable', 'date'],
            "Signatarios.*.DadosAssinatura.DadosPessoaFisica.NomePessoaFisica" => ['nullable', 'string'],
            "Signatarios.*.DadosAssinatura.DadosPessoaFisica.TipoDocumentoPessoaFisica" => ['nullable', 'string'],
            "Signatarios.*.DadosAssinatura.DadosPessoaFisica.NumeroDocumentoPessoaFisica" => ['required', 'string'],
            "Signatarios.*.DadosAssinatura.DadosPessoaJuridica.NomeEmpresa" => ['nullable', 'string'],
            "Signatarios.*.DadosAssinatura.DadosPessoaJuridica.TipoDocumentoEmpresa" => ['nullable', 'string'],
            "Signatarios.*.DadosAssinatura.DadosPessoaJuridica.NumeroDocumentoEmpresa" => ['nullable', 'string'],
            "Signatarios.*.DadosAssinatura.Anexos" => ['nullable', 'array'],
            "Signatarios.*.DadosAssinatura.Anexos.*.Id" => ['nullable', 'uuid'],
            "Signatarios.*.DadosAssinatura.Anexos.*.AnexoDocumentoNome" => ['nullable', 'string'],
            "Signatarios.*.DadosAssinatura.Anexos.*.LinkParaBaixarDocumento" => ['nullable', 'url'],

            "Documentos.*.Id" => ['uuid'],
            "Documentos.*.OrdemDocumento" => ['integer'],
            "Documentos.*.NomeDocumento" => ['string'],
            "Documentos.*.Base64Documento" => ['required', 'string'],
            "Documentos.*.LinkDocumentosCompartilhados" => ['nullable', 'url'],
            "Documentos.*.RegistroAssinaturas.*.NomeRegistro" => ['string'],
            "Documentos.*.RegistroAssinaturas.*.Base64Registro" => ['string'],

        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(json_decode($this->getContent(), true));
    }
}
