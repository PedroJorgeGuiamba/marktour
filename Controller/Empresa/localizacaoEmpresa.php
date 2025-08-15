<?php
session_start();

require_once __DIR__ . '/../../Model/Localizacao.php';
require_once __DIR__ . '/../../Helpers/Actividade.php';
require_once __DIR__ . '/../../Conexao/conector.php';

class FormularioDeLocalizacao
{
    public function criarLocalizacao()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $localizacao = new Localizacao();
                $id_empresa = isset($_GET['id']) ? (int) $_GET['id'] : null;

                if (!$id_empresa) {
                    echo "ID da empresa não fornecido.";
                    return;
                }

                $provincia = trim($_POST['provincia'] ?? '');
                $distrito = trim($_POST['distrito'] ?? '');
                $bairro = trim($_POST['bairro'] ?? '');
                $posto_administrativo = trim($_POST['postoAdministrativo'] ?? '');
                $localidade = trim($_POST['localidade'] ?? '');
                $avenida = trim($_POST['avenida'] ?? '');
                $rua = trim($_POST['rua'] ?? '');
                $andar = trim($_POST['andar'] ?? '');
                $endereco_detalhado = trim($_POST['endereco'] ?? '');
                $codigo_postal = trim($_POST['codigoPostal'] ?? '');

                $localizacao->setId_empresa($id_empresa);
                $localizacao->setProvincia($provincia);
                $localizacao->setDistrito($distrito);
                $localizacao->setBairro($bairro);
                $localizacao->setPosto_administrativo($posto_administrativo);
                $localizacao->setLocalidade($localidade);
                $localizacao->setAvenida($avenida);
                $localizacao->setRua($rua);
                $localizacao->setAndar($andar);
                $localizacao->setEndereco_detalhado($endereco_detalhado);
                $localizacao->setCodigo_postal($codigo_postal);

                if ($localizacao->salvar()) {
                    if (isset($_SESSION['sessao_id'])) {
                        registrarAtividade($_SESSION['sessao_id'], "Localização criada", "CRIACAO");
                    }

                    header("Location: /marktour/View/Empresa/empresa.php?id=" . $id_empresa);
                    exit();
                } else {
                    echo "Erro ao criar localização.";
                }
            } catch (Exception $e) {
                echo "Erro no sistema: " . $e->getMessage();
            }
        } else {
            echo "Método inválido.";
        }
    }
}

$controller = new FormularioDeLocalizacao();
$controller->criarLocalizacao();