<?php
require_once __DIR__ . '/../../Conexao/conector.php';

class Empresa
{
    private $id_empresa;
    private $id_utilizador;
    private $nome;
    private $nuit;
    private $descricao;
    private $estado;
    private $data_registro;

    public function __construct()
    {
        $this->data_registro = date('Y-m-d H:i:s');
        $this->estado = 'ativo';
    }

    // Getters
    public function getId_empresa() { return $this->id_empresa; }
    public function getId_utilizador() { return $this->id_utilizador; }
    public function getNome() { return $this->nome; }
    public function getNuit() { return $this->nuit; }
    public function getDescricao() { return $this->descricao; }
    public function getEstado() { return $this->estado; }
    public function getData_registro() { return $this->data_registro; }

    // Setters
    public function setId_empresa($id_empresa) { $this->id_empresa = $id_empresa; }
    public function setId_utilizador($id_utilizador) { $this->id_utilizador = $id_utilizador; }
    public function setNome($nome) { $this->nome = $nome; }
    public function setNuit($nuit) { $this->nuit = $nuit; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setData_registro($data_registro) { $this->data_registro = $data_registro; }

    public function salvar()
    {
        $conexao = new Conector();
        $conn = $conexao->getConexao();

        $sql = "INSERT INTO empresa (id_utilizador,nome, nuit, descricao, estado, data_registro) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $this->id_utilizador, $this->nuit, $this->descricao, $this->estado, $this->data_registro);

        return $stmt->execute();
    }
}