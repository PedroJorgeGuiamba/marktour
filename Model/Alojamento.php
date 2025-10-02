<?php
require_once __DIR__ . '/../Conexao/conector.php';

class Alojamento
{
    private $id_alojamento;
    private $nome;
    private $tipo;
    private $descricao;
    private $precoPorNoite;
    private $numQuartos;
    private $id_empresa;
    private $ImagemPath;


    // Getters
    public function getId_empresa() { return $this->id_empresa; }
    public function getNome() { return $this->nome; }
    public function getTipo() { return $this->tipo; }
    public function getDescricao() { return $this->descricao; }
    public function getPrecoPorNoite() { return $this->precoPorNoite; }
    public function getNumQuartos() { return $this->numQuartos; }
    public function getImagemPath() { return $this->ImagemPath; }

    // Setters
    public function setNome($nome) { $this->nome = $nome; }
    public function setId_empresa($id_empresa) { $this->id_empresa = $id_empresa; }
    public function setTipo($tipo) { $this->tipo = $tipo; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }
    public function setPrecoPorNoite($precoPorNoite) { $this->precoPorNoite = $precoPorNoite; }
    public function setNumQuartos($numQuartos) { $this->numQuartos = $numQuartos; }
    public function setImagemPath($ImagemPath) { $this->ImagemPath = $ImagemPath; }

    public function salvar()
    {
        $conexao = new Conector();
        $conn = $conexao->getConexao();

        $sql = "INSERT INTO alojamento (nome, tipo, descricao, preco_noite, num_quartos, id_empresa, imagem_path) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdiis", $this->nome, $this->tipo, $this->descricao, $this->precoPorNoite, $this->numQuartos, $this->id_empresa, $this->ImagemPath);

        return $stmt->execute();
    }
}