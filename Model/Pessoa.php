<?php
namespace Models;

use DateTime;

class Pessoa{
    private string $nome;
    private string $apelido;
    private int $codigo;
    private DateTime $dataDeNascimento;
    private string $naturalidade;
    private string $tipoDeDocumento;
    private string $numeroDeDocumento;
    private string $localEmitido;
    private DateTime $dataDeEmissao;
    private int $NUIT;
    private int $Telefone;
    private string $email;

    public function __construct(string $nome, string $apelido, int $codigo, DateTime $dataDeNascimento,string $naturalidade, string $tipoDeDocumento, string $numeroDeDocumento, string $localEmitido, DateTime $dataDeEmissao, int $NUIT, int $Telefone, string $email) {
        $this->nome = $nome;
        $this->apelido = $apelido;
        $this->codigo = $codigo;
        $this->dataDeNascimento = $dataDeNascimento;
        $this->naturalidade = $naturalidade;
        $this->tipoDeDocumento = $tipoDeDocumento;
        $this->numeroDeDocumento = $numeroDeDocumento;
        $this->localEmitido = $localEmitido;
        $this->dataDeEmissao = $dataDeEmissao;
        $this->NUIT = $NUIT;
        $this->Telefone = $Telefone;
        $this->email = $email;
    }
}

?>