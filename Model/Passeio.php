<?php
require_once __DIR__ . '/../Conexao/conector.php';

class Passeio
{
    private $nome;
    private $duracao;
    private $descricao;
    private $local;
    private $preco;
    private $dataHora;
    private $id_empresa;
    private $ImagemPath;
    
    
    // Getters
    public function getId_empresa() { return $this->id_empresa; }
    public function getNome() { return $this->nome; }
    public function getDuracao() { return $this->duracao; }
    public function getLocal() { return $this->local; }
    public function getDescricao() { return $this->descricao; }
    public function getPreco() { return $this->preco; }
    public function getDataHora() { return $this->dataHora; }
    public function getImagemPath() { return $this->ImagemPath; }
    
    // Setters
    public function setNome($nome) { $this->nome = $nome; }
    public function setId_empresa($id_empresa) { $this->id_empresa = $id_empresa; }
    public function setDuracao($duracao) { $this->duracao = $duracao; }
    public function setLocal($local) { $this->local = $local; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }
    public function setPreco($preco) { $this->preco = $preco; }
    public function setDataHora($dataHora) { $this->dataHora = $dataHora; }
    public function setImagemPath($ImagemPath) { $this->ImagemPath = $ImagemPath; }
    
    public function salvar()
    {
        $conexao = new Conector();
        $conn = $conexao->getConexao();
        
        $sql = "INSERT INTO actividade (nome, duracao, descricao, preco, local, data_hora, id_empresa, imagem_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdssis", $this->nome, $this->duracao, $this->descricao, $this->preco, $this->local, $this->dataHora, $this->id_empresa, $this->ImagemPath);

        return $stmt->execute();
    }
}