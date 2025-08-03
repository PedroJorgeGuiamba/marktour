<?php

use Models\Pessoa;
class Formando extends Pessoa{
    public function __construct(
        string $nome,
        string $apelido,
        int $codigo,
        DateTime $dataDeNascimento,
        string $naturalidade,
        string $tipoDeDocumento,
        string $numeroDeDocumento,
        string $localEmitido,
        DateTime $dataDeEmissao,
        int $NUIT,
        int $Telefone,
        string $email,
        string $curso
    ) {
        parent::__construct($nome, $apelido, $codigo, $dataDeNascimento, $naturalidade, $tipoDeDocumento, $numeroDeDocumento, $localEmitido, $dataDeEmissao, $NUIT, $Telefone, $email);
    }
}

?>