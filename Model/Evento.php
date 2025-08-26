<?php
require_once __DIR__ . '/../Conexao/conector.php';

class Evento
{
    private $id_evento;
    private $nome;
    private $descricao;
    private $data_evento;
    private $hora_inicio;
    private $hora_fim;
    private $local;
    private $organizador;
    private $status;

    // Getters
    public function getId_evento() { return $this->id_evento; }
    public function getNome() { return $this->nome; }
    public function getDescricao() { return $this->descricao; }
    public function getData_evento() { return $this->data_evento; }
    public function getHora_inicio() { return $this->hora_inicio; }
    public function getHora_fim() { return $this->hora_fim; }
    public function getLocal() { return $this->local; }
    public function getOrganizador() { return $this->organizador; }
    public function getStatus() { return $this->status; }

    // Setters
    public function setId_evento($id_evento) { $this->id_evento = $id_evento; }
    public function setNome($nome) { $this->nome = $nome; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }
    public function setData_evento($data_evento) { $this->data_evento = $data_evento; }
    public function setHora_inicio($hora_inicio) { $this->hora_inicio = $hora_inicio; }
    public function setHora_fim($hora_fim) { $this->hora_fim = $hora_fim; }
    public function setLocal($local) { $this->local = $local; }
    public function setOrganizador($organizador) { $this->organizador = $organizador; }
    public function setStatus($status) { $this->status = $status; }

    public function salvar($conn)
    {
        $sql = "INSERT INTO eventos (nome, descricao, data_evento, hora_inicio, hora_fim, local, organizador, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $this->nome, $this->descricao, $this->data_evento, $this->hora_inicio, $this->hora_fim, $this->local, $this->organizador, $this->status);

        $success = $stmt->execute();
        if ($success) {
            return true;
        } else {
            error_log("Erro no INSERT na tabela eventos: " . $conn->error);
            return false;
        }
    }
}