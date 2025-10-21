<?php
require_once __DIR__ . '/../Conexao/conector.php';

class Faq
{
    private $id_utilizador;
    private $pergunta;


    // Getters
    public function getId_utilizador() { return $this->id_utilizador; }
    public function getPergunta() { return $this->pergunta; }

    // Setters
    public function setId_utilizador($id_utilizador) { $this->id_utilizador = $id_utilizador; }
    public function setPergunta($pergunta){$this->pergunta = $pergunta; }

    public function salvar($conn)
    {
        $sql = "INSERT INTO faq (pergunta, id_utilizador, resposta) VALUES (?, ?, NULL)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $this->pergunta, $this->id_utilizador);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $error = $conn->error;
            $stmt->close();
            error_log("Erro ao salvar FAQ: " . $error);
            throw new Exception("Erro ao salvar a pergunta: " . $error);
        }
    }
}
