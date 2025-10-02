<?php
require_once __DIR__ . '/../Conexao/conector.php';

class Empresa
{
    private $id_empresa;
    private $id_utilizador;
    private $id_localizacao;
    private $nome;
    private $nuit;
    private $descricao;
    private $estado;
    private $data_registro;
    private $ImagemNuitPath;
    private $ImagemAlvaraPath;
    private $NumAlvara;

    public function __construct()
    {
        $this->data_registro = date('Y-m-d H:i:s');
        $this->estado = 'aprovado';
    }

    // Getters
    public function getId_empresa() { return $this->id_empresa; }
    public function getId_localizacao() { return $this->id_localizacao; }
    public function getId_utilizador() { return $this->id_utilizador; }
    public function getNome() { return $this->nome; }
    public function getNuit() { return $this->nuit; }
    public function getDescricao() { return $this->descricao; }
    public function getEstado() { return $this->estado; }
    public function getData_registro() { return $this->data_registro; }

    // Setters
    public function setId_empresa($id_empresa) { $this->id_empresa = $id_empresa; }
    public function setId_localizacao($id_localizacao) { $this->id_localizacao = $id_localizacao; }
    public function setId_utilizador($id_utilizador) { $this->id_utilizador = $id_utilizador; }
    public function setNome($nome) { $this->nome = $nome; }
    public function setNuit($nuit) { $this->nuit = $nuit; }
    public function setNumAlvara($NumAlvara) { $this->NumAlvara = $NumAlvara; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setData_registro($data_registro) { $this->data_registro = $data_registro; }
    public function setImagemNuitPath($ImagemNuitPath) { $this->ImagemNuitPath = $ImagemNuitPath; }
    public function setImagemAlvaraPath($ImagemAlvaraPath) { $this->ImagemAlvaraPath = $ImagemAlvaraPath; }

    public function salvar($conn)
    {
        
        $sql = "INSERT INTO empresa (id_utilizador, id_localizacao, nome, nuit, descricao, estado_verificacao, data_registo, imagem_nuit_path, imagem_alvara_path, numAlvara) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisissssss", $this->id_utilizador,$this->id_localizacao, $this->nome, $this->nuit, $this->descricao, $this->estado, $this->data_registro, $this->ImagemNuitPath, $this->ImagemAlvaraPath, $this->NumAlvara);

        $success = $stmt->execute();
        if ($success) {
            return true;
        } else {
            error_log("Erro no INSERT na tabela empresa: " . $conn->error);
            return false;
        }
    }
}