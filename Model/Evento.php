<?php
require_once __DIR__ . '/../Conexao/conector.php';

class Evento
{
    private $id_empresa;
    private $nome;
    private $descricao;
    private $data_evento;
    private $hora_inicio;
    private $hora_fim;
    private $local;
    private $organizador;
    private $status;

    // Getters
    public function getId_empresa() { return $this->id_empresa; }
    public function getNome() { return $this->nome; }
    public function getDescricao() { return $this->descricao; }
    public function getData_evento() { return $this->data_evento; }
    public function getHora_inicio() { return $this->hora_inicio; }
    public function getHora_fim() { return $this->hora_fim; }
    public function getLocal() { return $this->local; }
    public function getOrganizador() { return $this->organizador; }
    public function getStatus() { return $this->status; }

    // Setters
    public function setId_empresa($id_empresa) { $this->id_empresa = $id_empresa; }
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
        $sql = "INSERT INTO eventos (nome, descricao, data_evento, hora_inicio, hora_fim, local, organizador, status, id_empresa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $this->nome, $this->descricao, $this->data_evento, $this->hora_inicio, $this->hora_fim, $this->local, $this->organizador, $this->status, $this->id_empresa);

        if ($stmt->execute()) {
            return $conn->insert_id; // Retorna o ID do evento inserido
        } else {
            error_log("Erro no INSERT na tabela eventos: " . $conn->error);
            return false;
        }
    }

    public function salvarMidias($conn, $id_evento, $midias)
    {
        $sql = "INSERT INTO eventos_midias (id_evento, tipo, path) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        foreach ($midias as $midia) {
            $tipo = $midia['tipo'];
            $path = $midia['path'];
            $stmt->bind_param("iss", $id_evento, $tipo, $path);
            if (!$stmt->execute()) {
                error_log("Erro ao inserir mídia na tabela eventos_midias: " . $conn->error);
                throw new Exception("Erro ao salvar mídia no banco de dados: " . $conn->error);
            }
        }
        $stmt->close();
    }
}