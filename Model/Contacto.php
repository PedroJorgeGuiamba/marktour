<?php
require_once __DIR__ . '/../Conexao/conector.php';

class Contato_empresa
{
    private $id_contato;
    private $id_empresa;
    private $telefone1;
    private $telefone2;
    private $telefone3;
    private $fax1;
    private $fax2;
    private $email;
    private $website;

    // Getters
    public function getId_contato() { return $this->id_contato; }
    public function getId_empresa() { return $this->id_empresa; }
    public function getTelefone1() { return $this->telefone1; }
    public function getTelefone2() { return $this->telefone2; }
    public function getTelefone3() { return $this->telefone3; }
    public function getFax1() { return $this->fax1; }
    public function getFax2() { return $this->fax2; }
    public function getEmail() { return $this->email; }
    public function getWebsite() { return $this->website; }

    // Setters
    public function setId_contato($id_contato) { $this->id_contato = $id_contato; }
    public function setId_empresa($id_empresa) { $this->id_empresa = $id_empresa; }
    public function setTelefone1($telefone1) { $this->telefone1 = $telefone1; }
    public function setTelefone2($telefone2) { $this->telefone2 = $telefone2; }
    public function setTelefone3($telefone3) { $this->telefone3 = $telefone3; }
    public function setFax1($fax1) { $this->fax1 = $fax1; }
    public function setFax2($fax2) { $this->fax2 = $fax2; }
    public function setEmail($email) { $this->email = $email; }
    public function setWebsite($website) { $this->website = $website; }

    public function salvar()
    {
        $conexao = new Conector();
        $conn = $conexao->getConexao();

        $sql = "INSERT INTO contacto_empresa (id_empresa, telefone1, telefone2, telefone3, fax1, fax2, email, website) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssss", $this->id_empresa, $this->telefone1, $this->telefone2, $this->telefone3, $this->fax1, $this->fax2, $this->email, $this->website);

        return $stmt->execute();
    }
}