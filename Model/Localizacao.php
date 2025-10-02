<?php
require_once __DIR__ . '/../Conexao/conector.php';

class Localizacao
{
    private $id_localizacao;
    // private $id_empresa;
    private $provincia;
    private $distrito;
    private $bairro;
    private $posto_administrativo;
    private $localidade;
    private $avenida;
    private $rua;
    private $andar;
    private $endereco_detalhado;
    private $codigo_postal;
    private $latitude;
    private $longitude;
    private $referencia;

    // Getters
    public function getId_localizacao() { return $this->id_localizacao; }
    // public function getId_empresa() { return $this->id_empresa; }
    public function getProvincia() { return $this->provincia; }
    public function getDistrito() { return $this->distrito; }
    public function getBairro() { return $this->bairro; }
    public function getPosto_administrativo() { return $this->posto_administrativo; }
    public function getLocalidade() { return $this->localidade; }
    public function getAvenida() { return $this->avenida; }
    public function getRua() { return $this->rua; }
    public function getAndar() { return $this->andar; }
    public function getEndereco_detalhado() { return $this->endereco_detalhado; }
    public function getCodigo_postal() { return $this->codigo_postal; }
    public function getLatitude(){ return $this->latitude; }
    public function getLongitude(){ return $this->longitude; }
    public function getReferencia() { return $this->referencia; }

    // Setters
    public function setId_localizacao($id_localizacao) { $this->id_localizacao = $id_localizacao; }
    // public function setId_empresa($id_empresa) { $this->id_empresa = $id_empresa; }
    public function setProvincia($provincia) { $this->provincia = $provincia; }
    public function setDistrito($distrito) { $this->distrito = $distrito; }
    public function setBairro($bairro) { $this->bairro = $bairro; }
    public function setPosto_administrativo($posto_administrativo) { $this->posto_administrativo = $posto_administrativo; }
    public function setLocalidade($localidade) { $this->localidade = $localidade; }
    public function setAvenida($avenida) { $this->avenida = $avenida; }
    public function setRua($rua) { $this->rua = $rua; }
    public function setAndar($andar) { $this->andar = $andar; }
    public function setEndereco_detalhado($endereco_detalhado) { $this->endereco_detalhado = $endereco_detalhado; }
    public function setCodigo_postal($codigo_postal) { $this->codigo_postal = $codigo_postal; }
    public function setLongitude($longitude) { $this->longitude = $longitude; }
    public function setLatitude($latitude) { $this->latitude = $latitude; }
    public function setReferencia($referencia) { $this->referencia = $referencia;}

    public function salvar($conn)
    {
        $sql = "INSERT INTO localizacao (provincia, distrito, bairro, posto_administrativo, localidade, avenida, rua, andar, endereco_detalhado, codigo_postal, latitude, longitude, referencia) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssssss", $this->provincia, $this->distrito, $this->bairro, $this->posto_administrativo, $this->localidade, $this->avenida, $this->rua, $this->andar, $this->endereco_detalhado, $this->codigo_postal, $this->latitude, $this->longitude, $this->referencia);

        $success = $stmt->execute();
        if ($success) {
            return true;
        } else {
            error_log("Erro no INSERT na tabela localizacao: " . $conn->error);
            return false;
        }
    }
}