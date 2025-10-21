<?php
session_start();
$id_empresa = isset($_GET['id_empresa']) ? (int)$_GET['id_empresa'] : null;

if (!$id_empresa) {
    die("Parâmetros inválidos. IDs de empresa não fornecidos.");
}
?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresa - Contatos</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                <li><a href="portalDaEmpresa.php">Home</a></li>
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
            <h2>Contato da Empresa</h2>
            <form action="../../Controller/Empresa/contactoEmpresa.php" method="post" id="formularioContacto">
                <input type="hidden" name="id_empresa" value="<?php echo $id_empresa; ?>">

                <div class="input-group">
                    <div>
                        <label for="telefone1">Telefone 1:</label>
                        <input type="tel" name="telefone1" id="telefone1" placeholder="85xxxxxxx">
                        <span class="error_form" id="telefone1_error_message"></span>
                    </div>
                    <div>
                        <label for="telefone2">Telefone 2:</label>
                        <input type="tel" name="telefone2" id="telefone2" placeholder="85xxxxxxx">
                        <span class="error_form" id="telefone2_error_message"></span>
                    </div>
                    <div>
                        <label for="telefone3">Telefone 3:</label>
                        <input type="tel" name="telefone3" id="telefone3" placeholder="85xxxxxxx">
                        <span class="error_form" id="telefone3_error_message"></span>
                    </div>
                    <div>
                        <label for="fax1">Fax 1:</label>
                        <input type="tel" name="fax1" id="fax1" placeholder="85xxxx">
                        <span class="error_form" id="fax1_error_message"></span>
                    </div>
                    <div>
                        <label for="fax2">Fax 2:</label>
                        <input type="tel" name="fax2" id="fax2" placeholder="85xxxx">
                        <span class="error_form" id="fax2_error_message"></span>
                    </div>
                    <div>
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" placeholder="marktour@gmail.com">
                        <span class="error_form" id="email_error_message"></span>
                    </div>
                    <div>
                        <label for="website">Website:</label>
                        <input type="text" name="website" id="website" placeholder="marktour.co.mz">
                        <span class="error_form" id="website_error_message"></span>
                    </div>
                </div>

                <button type="submit" class="btn">Register</button>
            </form>
        </div>
    </main>

    <!-- jQuery Validation existente -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
        $("#formularioContacto").validate({
            rules: {
                telefone1: { required: true, pattern: /^(\+258)?[ -]?[8][2-7][0-9]{7}$/ },
                telefone2: { required: false, pattern: /^(\+258)?[ -]?[8][2-7][0-9]{7}$/ },
                telefone3: { required: false, pattern: /^(\+258)?[ -]?[8][2-7][0-9]{7}$/ },
                fax1: { required: false, digits: true, minlength: 6, maxlength: 9 },
                fax2: { required: false, digits: true, minlength: 6, maxlength: 9 },
                email: { required: true, email: true },
                website: { required: false, url: true }
            },
            messages: {
                telefone1: { required: "Informe o telefone principal.", pattern: "Número inválido. Ex: +258 84xxxxxxx" },
                telefone2: { pattern: "Número inválido. Ex: +258 84xxxxxxx" },
                telefone3: { pattern: "Número inválido. Ex: +258 84xxxxxxx" },
                fax1: { digits: "Apenas números são permitidos.", minlength: "O fax deve ter entre 6 e 9 dígitos.", maxlength: "O fax deve ter entre 6 e 9 dígitos." },
                fax2: { digits: "Apenas números são permitidos.", minlength: "O fax deve ter entre 6 e 9 dígitos.", maxlength: "O fax deve ter entre 6 e 9 dígitos." },
                email: { required: "Informe o e-mail.", email: "Endereço de e-mail inválido." },
                website: { url: "Informe um URL válido. Ex: https://example.com" }
            },
            errorClass: "is-invalid",
            validClass: "is-valid",
            highlight: function(element) { $(element).addClass("is-invalid").removeClass("is-valid"); },
            unhighlight: function(element) { $(element).removeClass("is-invalid").addClass("is-valid"); },
            errorPlacement: function(error, element) { error.insertAfter(element); }
        });
    </script>
</body>

</html>
