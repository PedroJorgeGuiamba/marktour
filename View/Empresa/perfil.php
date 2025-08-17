<?php
session_start();
// include './Controller/Utilizador/Home.php';
include '../../Controller/Empresa/Home.php';
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Informações - MarkTour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Style/home.css">
    <style>
        .card { margin-bottom: 20px; }
        .no-data { color: #dc3545; }
        .modal .form-group { margin-bottom: 1rem; }
        .modal-footer .btn { margin: 0 5px; }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <img src="http://marktour.co.mz/wp-content/uploads/2022/04/Logo-Marktour-PNG-SEM-FUNDO1.png.webp">
                <div class="nav-modal">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarText">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.instagram.com/marktourreservasonline/">Instagram</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://web.facebook.com/marktour.ei?_rdc=1&_rdr#">Facebook</a>
                            </li>
                            <li class="nav-item">
                                <a href="/Controller/Auth/LogoutController.php" class="btn btn-danger">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <nav>
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownModulos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Acomodações
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownModulos">
                        <li><a class="dropdown-item" href="#">Hoteis</a></li>
                        <li><a class="dropdown-item" href="#">Resorts</a></li>
                        <li><a class="dropdown-item" href="#">Lounges</a></li>
                        <li><a class="dropdown-item" href="#">Casas De Praia</a></li>
                        <li><a class="dropdown-item" href="#">Apartamentos</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownModulos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Passeios
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownModulos">
                        <li><a class="dropdown-item" href="#">A Pe</a></li>
                        <li><a class="dropdown-item" href="#">De Carro</a></li>
                        <li><a class="dropdown-item" href="#">De Barco</a></li>
                        <li><a class="dropdown-item" href="#">De Jet Ski</a></li>
                        <li><a class="dropdown-item" href="#">De Moto</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Eventos</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownModulos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        MarkTour
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownModulos">
                        <li><a class="dropdown-item" href="#">Sobre</a></li>
                        <li><a class="dropdown-item" href="#">Contactos</a></li>
                        <li><a class="dropdown-item" href="#">FAQ</a></li>
                        <li><a class="dropdown-item" href="#">Blog</a></li>
                        <li><a class="dropdown-item" href="#">Reviews</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container mt-5" id="infoContainer">
        <h2>Informações Recuperadas</h2>
        <div id="loading" class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
        </div>
    </main>

    <!-- Modal para Edição da Empresa -->
    <div class="modal fade" id="editEmpresaModal" tabindex="-1" aria-labelledby="editEmpresaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmpresaModalLabel">Editar Dados da Empresa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editEmpresaForm">
                        <input type="hidden" id="editEmpresaId" name="id_empresa">
                        <div class="form-group">
                            <label for="editNome">Nome</label>
                            <input type="text" class="form-control" id="editNome" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="editNuit">NUIT</label>
                            <input type="text" class="form-control" id="editNuit" name="nuit" required>
                        </div>
                        <div class="form-group">
                            <label for="editDescricao">Descrição</label>
                            <input type="text" class="form-control" id="editDescricao" name="descricao" required>
                        </div>
                        <div class="form-group">
                            <label for="editEstado">Estado</label>
                            <select class="form-control" id="editEstado" name="estado" required>
                                <option value="aprovado">Aprovado</option>
                                <option value="pendente">Pendente</option>
                                <option value="rejeitado">Rejeitado</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="saveEmpresaChanges">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Edição da Localização -->
    <div class="modal fade" id="editLocalizacaoModal" tabindex="-1" aria-labelledby="editLocalizacaoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLocalizacaoModalLabel">Editar Localização</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editLocalizacaoForm">
                        <input type="hidden" id="editLocalizacaoId" name="id_localizacao">
                        <div class="form-group">
                            <label for="editProvincia">Província</label>
                            <input type="text" class="form-control" id="editProvincia" name="provincia" required>
                        </div>
                        <div class="form-group">
                            <label for="editDistrito">Distrito</label>
                            <input type="text" class="form-control" id="editDistrito" name="distrito" required>
                        </div>
                        <div class="form-group">
                            <label for="editBairro">Bairro</label>
                            <input type="text" class="form-control" id="editBairro" name="bairro" required>
                        </div>
                        <div class="form-group">
                            <label for="editPostoAdministrativo">Posto Administrativo</label>
                            <input type="text" class="form-control" id="editPostoAdministrativo" name="posto_administrativo" required>
                        </div>
                        <div class="form-group">
                            <label for="editLocalidade">Localidade</label>
                            <input type="text" class="form-control" id="editLocalidade" name="localidade" required>
                        </div>
                        <div class="form-group">
                            <label for="editAvenida">Avenida</label>
                            <input type="text" class="form-control" id="editAvenida" name="avenida">
                        </div>
                        <div class="form-group">
                            <label for="editRua">Rua</label>
                            <input type="text" class="form-control" id="editRua" name="rua">
                        </div>
                        <div class="form-group">
                            <label for="editAndar">Andar</label>
                            <input type="text" class="form-control" id="editAndar" name="andar">
                        </div>
                        <div class="form-group">
                            <label for="editEnderecoDetalhado">Endereço Detalhado</label>
                            <input type="text" class="form-control" id="editEnderecoDetalhado" name="endereco_detalhado">
                        </div>
                        <div class="form-group">
                            <label for="editCodigoPostal">Código Postal</label>
                            <input type="text" class="form-control" id="editCodigoPostal" name="codigo_postal">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="saveLocalizacaoChanges">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Edição do Contato -->
    <div class="modal fade" id="editContatoModal" tabindex="-1" aria-labelledby="editContatoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editContatoModalLabel">Editar Contato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editContatoForm">
                        <input type="hidden" id="editContatoId" name="id_empresa">
                        <div class="form-group">
                            <label for="editTelefone1">Telefone 1</label>
                            <input type="tel" class="form-control" id="editTelefone1" name="telefone1" required>
                        </div>
                        <div class="form-group">
                            <label for="editTelefone2">Telefone 2</label>
                            <input type="tel" class="form-control" id="editTelefone2" name="telefone2">
                        </div>
                        <div class="form-group">
                            <label for="editTelefone3">Telefone 3</label>
                            <input type="tel" class="form-control" id="editTelefone3" name="telefone3">
                        </div>
                        <div class="form-group">
                            <label for="editFax1">Fax 1</label>
                            <input type="tel" class="form-control" id="editFax1" name="fax1">
                        </div>
                        <div class="form-group">
                            <label for="editFax2">Fax 2</label>
                            <input type="tel" class="form-control" id="editFax2" name="fax2">
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="editWebsite">Website</label>
                            <input type="text" class="form-control" id="editWebsite" name="website">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="saveContatoChanges">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            let data = null;
            $('#loading').hide();

            // Carregar dados iniciais
            $.ajax({
                url: '/marktour/Controller/Empresa/perfil.php', // Caminho ajustado para o contexto do projeto
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    data = response;
                    $('#loading').hide();
                    if (!data.empresa) {
                        $('#infoContainer').append('<div class="alert alert-warning">Nenhuma empresa encontrada para este utilizador.</div>');
                        return;
                    }

                    // Exibir dados
                    let utilizadorHtml = `
                        <div class="card mb-4">
                            <div class="card-header"><strong>Dados do Utilizador</strong></div>
                            <div class="card-body">
                                <p><strong>Nome:</strong> ${data.utilizador.nome}</p>
                                <p><strong>Email:</strong> ${data.utilizador.email}</p>
                            </div>
                        </div>
                    `;
                    let empresaHtml = `
                        <div class="card mb-4">
                            <div class="card-header"><strong>Dados da Empresa</strong> <button class="btn btn-sm btn-primary float-end" data-bs-toggle="modal" data-bs-target="#editEmpresaModal">Editar</button></div>
                            <div class="card-body">
                                <p><strong>Nome:</strong> ${data.empresa.nome}</p>
                                <p><strong>NUIT:</strong> ${data.empresa.nuit}</p>
                                <p><strong>Descrição:</strong> ${data.empresa.descricao}</p>
                                <p><strong>Estado:</strong> ${data.empresa.estado_verificacao}</p> <!-- Ajuste para estado_verificacao -->
                                <p><strong>Data de Registro:</strong> ${data.empresa.data_registro}</p>
                            </div>
                        </div>
                    `;
                    let localizacaoHtml = `
                        <div class="card mb-4">
                            <div class="card-header"><strong>Localização</strong> <button class="btn btn-sm btn-primary float-end" data-bs-toggle="modal" data-bs-target="#editLocalizacaoModal">Editar</button></div>
                            <div class="card-body">
                                <p><strong>Província:</strong> ${data.localizacao.provincia}</p>
                                <p><strong>Distrito:</strong> ${data.localizacao.distrito}</p>
                                <p><strong>Bairro:</strong> ${data.localizacao.bairro}</p>
                                <p><strong>Posto Administrativo:</strong> ${data.localizacao.posto_administrativo}</p>
                                <p><strong>Localidade:</strong> ${data.localizacao.localidade}</p>
                                <p><strong>Avenida:</strong> ${data.localizacao.avenida}</p>
                                <p><strong>Rua:</strong> ${data.localizacao.rua}</p>
                                <p><strong>Andar:</strong> ${data.localizacao.andar}</p>
                                <p><strong>Endereço Detalhado:</strong> ${data.localizacao.endereco_detalhado}</p>
                                <p><strong>Código Postal:</strong> ${data.localizacao.codigo_postal}</p>
                            </div>
                        </div>
                    `;
                    let contactoHtml = `
                        <div class="card mb-4">
                            <div class="card-header"><strong>Contato</strong> <button class="btn btn-sm btn-primary float-end" data-bs-toggle="modal" data-bs-target="#editContatoModal">Editar</button></div>
                            <div class="card-body">
                                <p><strong>Telefone 1:</strong> ${data.contacto.telefone1 || 'Não informado'}</p>
                                <p><strong>Telefone 2:</strong> ${data.contacto.telefone2 || 'Não informado'}</p>
                                <p><strong>Telefone 3:</strong> ${data.contacto.telefone3 || 'Não informado'}</p>
                                <p><strong>Fax 1:</strong> ${data.contacto.fax1 || 'Não informado'}</p>
                                <p><strong>Fax 2:</strong> ${data.contacto.fax2 || 'Não informado'}</p>
                                <p><strong>Email:</strong> ${data.contacto.email || 'Não informado'}</p>
                                <p><strong>Website:</strong> ${data.contacto.website || 'Não informado'}</p>
                            </div>
                        </div>
                    `;
                    $('#infoContainer').append(utilizadorHtml + empresaHtml + localizacaoHtml + contactoHtml);

                    // Preencher modais com dados iniciais
                    $('#editEmpresaId').val(data.empresa.id_empresa);
                    $('#editNome').val(data.empresa.nome);
                    $('#editNuit').val(data.empresa.nuit);
                    $('#editDescricao').val(data.empresa.descricao);
                    $('#editEstado').val(data.empresa.estado_verificacao); // Ajuste para estado_verificacao

                    $('#editLocalizacaoId').val(data.empresa.id_localizacao);
                    $('#editProvincia').val(data.localizacao.provincia);
                    $('#editDistrito').val(data.localizacao.distrito);
                    $('#editBairro').val(data.localizacao.bairro);
                    $('#editPostoAdministrativo').val(data.localizacao.posto_administrativo);
                    $('#editLocalidade').val(data.localizacao.localidade);
                    $('#editAvenida').val(data.localizacao.avenida);
                    $('#editRua').val(data.localizacao.rua);
                    $('#editAndar').val(data.localizacao.andar);
                    $('#editEnderecoDetalhado').val(data.localizacao.endereco_detalhado);
                    $('#editCodigoPostal').val(data.localizacao.codigo_postal);

                    $('#editContatoId').val(data.empresa.id_empresa);
                    $('#editTelefone1').val(data.contacto.telefone1 || '');
                    $('#editTelefone2').val(data.contacto.telefone2 || '');
                    $('#editTelefone3').val(data.contacto.telefone3 || '');
                    $('#editFax1').val(data.contacto.fax1 || '');
                    $('#editFax2').val(data.contacto.fax2 || '');
                    $('#editEmail').val(data.contacto.email || '');
                    $('#editWebsite').val(data.contacto.website || '');
                },
                error: function(xhr, status, error) {
                    $('#loading').hide();
                    $('#infoContainer').append('<div class="alert alert-danger">Erro ao carregar os dados. Tente novamente mais tarde.</div>');
                    console.error('Erro AJAX: ', status, error);
                }
            });

            // Salvar alterações da Empresa
            $('#saveEmpresaChanges').click(function() {
                let formData = {
                    acao: 'atualizar_empresa',
                    id_empresa: $('#editEmpresaId').val(),
                    nome: $('#editNome').val(),
                    nuit: $('#editNuit').val(),
                    descricao: $('#editDescricao').val(),
                    estado_verificacao: $('#editEstado').val() // Ajuste para estado_verificacao
                };
                $.ajax({
                    url: '/marktour/Controller/Empresa/perfil.php', // Caminho ajustado para o contexto do projeto
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(formData),
                    success: function(response) {
                        if (response.success) {
                            alert('Dados da empresa atualizados com sucesso!');
                            location.reload();
                        } else {
                            alert('Erro ao atualizar os dados da empresa.');
                        }
                    },
                    error: function() {
                        alert('Erro na requisição.');
                    }
                });
            });

            // Salvar alterações da Localização
            $('#saveLocalizacaoChanges').click(function() {
                let formData = {
                    acao: 'atualizar_localizacao',
                    id_localizacao: $('#editLocalizacaoId').val(),
                    provincia: $('#editProvincia').val(),
                    distrito: $('#editDistrito').val(),
                    bairro: $('#editBairro').val(),
                    posto_administrativo: $('#editPostoAdministrativo').val(),
                    localidade: $('#editLocalidade').val(),
                    avenida: $('#editAvenida').val(),
                    rua: $('#editRua').val(),
                    andar: $('#editAndar').val(),
                    endereco_detalhado: $('#editEnderecoDetalhado').val(),
                    codigo_postal: $('#editCodigoPostal').val()
                };
                $.ajax({
                    url: '/marktour/Controller/Empresa/perfil.php', // Caminho ajustado para o contexto do projeto
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(formData),
                    success: function(response) {
                        if (response.success) {
                            alert('Localização atualizada com sucesso!');
                            location.reload();
                        } else {
                            alert('Erro ao atualizar a localização.');
                        }
                    },
                    error: function() {
                        alert('Erro na requisição.');
                    }
                });
            });

            // Salvar alterações do Contato
            $('#saveContatoChanges').click(function() {
                let formData = {
                    acao: 'atualizar_contato',
                    id_empresa: $('#editContatoId').val(),
                    telefone1: $('#editTelefone1').val(),
                    telefone2: $('#editTelefone2').val(),
                    telefone3: $('#editTelefone3').val(),
                    fax1: $('#editFax1').val(),
                    fax2: $('#editFax2').val(),
                    email: $('#editEmail').val(),
                    website: $('#editWebsite').val()
                };
                $.ajax({
                    url: '/marktour/Controller/Empresa/perfil.php', // Caminho ajustado para o contexto do projeto
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(formData),
                    success: function(response) {
                        if (response.success) {
                            alert('Contato atualizado com sucesso!');
                            location.reload();
                        } else {
                            alert('Erro ao atualizar o contato.');
                        }
                    },
                    error: function() {
                        alert('Erro na requisição.');
                    }
                });
            });
        });
    </script>
</body>
</html>