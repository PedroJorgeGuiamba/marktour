<?php
session_start();
include '../../Controller/Empresa/Home.php';
// Verificar se os IDs foram passados via query string
$id_utilizador = isset($_GET['id_utilizador']) ? (int)$_GET['id_utilizador'] : null;
$id_localizacao = isset($_GET['id_localizacao']) ? (int)$_GET['id_localizacao'] : null;

if (!$id_utilizador || !$id_localizacao) {
    die("Parâmetros inválidos. IDs de utilizador ou localização não fornecidos.");
}
?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresa - Cadastro</title>
    <link rel="stylesheet" href="../../Style/form.css">
</head>

<body>
    <header>
        <div class="nav-main">
            <img src="http://marktour.co.mz/wp-content/uploads/2022/04/Logo-Marktour-PNG-SEM-FUNDO1.png.webp"
                alt="Marktour Logo">
            <a href="/Controller/Auth/LogoutController.php" class="btn-logout">Logout</a>
        </div>

        <nav class="nav-secondary">
            <ul>
                <li><a href="#">Home</a></li>
                <li>
                    <a href="#">Acomodações</a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Hoteis</a></li>
                        <li><a href="#">Resorts</a></li>
                        <li><a href="#">Lounges</a></li>
                        <li><a href="#">Casas De Praia</a></li>
                        <li><a href="#">Apartamentos</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">Passeios</a>
                    <ul class="dropdown-menu">
                        <li><a href="#">A Pe</a></li>
                        <li><a href="#">De Carro</a></li>
                        <li><a href="#">De Barco</a></li>
                        <li><a href="#">De Jet Ski</a></li>
                        <li><a href="#">De Moto</a></li>
                    </ul>
                </li>
                <li><a href="#">Eventos</a></li>
                <li>
                    <a href="#">MarkTour</a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Sobre</a></li>
                        <li><a href="#">Contactos</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Reviews</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>Cadastro da Empresa</h2>

            <form action="../../Controller/Empresa/empresa.php" method="post" id="formularioEmpresa">
                <input type="hidden" name="id_utilizador" value="<?php echo $id_utilizador; ?>">
                <input type="hidden" name="id_localizacao" value="<?php echo $id_localizacao; ?>">

                <div class="input-group">
                    <div>
                        <label for="nome">Nome da Empresa:</label>
                        <input type="text" name="nome" id="nome" placeholder="Marktour">
                        <span class="error_form" id="nome_error_message"></span>
                    </div>

                    <div>
                        <label for="nuit">NUIT:</label>
                        <input type="text" name="nuit" id="nuit" placeholder="85xxxxxxx">
                        <span class="error_form" id="nuit_error_message"></span>
                    </div>

                    <div>
                        <label for="descricao">Descrição:</label>
                        <input type="text" name="descricao" id="descricao" placeholder="Descrição da empresa">
                        <span class="error_form" id="descricao_error_message"></span>
                    </div>
                </div>

                <button type="submit" class="btn">Register</button>
            </form>
        </div>
    </main>

    <!-- jQuery Validation existente -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
        $("#formularioEmpresa").validate({
            rules: {
                nome: {
                    required: true,
                    minlength: 2
                },
                nuit: {
                    required: true,
                    digits: true,
                    minlength: 9,
                    maxlength: 9
                },
                descricao: {
                    required: true,
                    minlength: 2
                }
            },
            messages: {
                nome: {
                    required: "Informe o nome da empresa.",
                    minlength: "O nome deve ter pelo menos 2 caracteres."
                },
                nuit: {
                    required: "Informe o NUIT.",
                    digits: "Apenas números são permitidos.",
                    minlength: "O NUIT deve ter 9 dígitos.",
                    maxlength: "O NUIT deve ter 9 dígitos."
                },
                descricao: {
                    required: "Informe a descrição.",
                    minlength: "A descrição deve ter pelo menos 2 caracteres."
                }
            },
            errorClass: "is-invalid",
            validClass: "is-valid",
            highlight: function(element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });
    </script>
</body>

</html>
