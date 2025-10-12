<?php
session_start();
include '../../Controller/Empresa/Home.php';
?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro da Empresa - Marktour</title>

    <link rel="stylesheet" href="../../Style/form.css">
</head>

<body>
    <header>
        <div style="display:flex; align-items:center; justify-content:space-between;">
            <img src="http://marktour.co.mz/wp-content/uploads/2022/04/Logo-Marktour-PNG-SEM-FUNDO1.png.webp"
                alt="Marktour Logo">
            <a href="../../Controller/Auth/LogoutController.php" class="btn"
                style="background:#e63946; color:#fff; text-decoration:none;">Logout</a>

        </div>

        <nav>
            <a href="#">Home</a>
            <a href="#">Acomodações</a>
            <a href="#">Passeios</a>
            <a href="#">Eventos</a>
            <a href="#">Sobre</a>
            <a href="#">Contactos</a>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>Cadastro de Localização</h2>

            <form id="multiStepForm" action="../../Controller/Empresa/localizacaoEmpresa.php" method="POST">

                <!-- Etapa 1 -->
                <div class="form-step active" id="step1">
                    <div class="input-group">
                        <div>
                            <label for="provincia">Província:</label>
                            <input type="text" name="provincia" id="provincia" placeholder="Maputo" required>
                            <span class="error" id="error-provincia"></span>
                        </div>
                        <div>
                            <label for="distrito">Distrito:</label>
                            <input type="text" name="distrito" id="distrito" placeholder="Kampfumo" required>
                            <span class="error" id="error-distrito"></span>
                        </div>
                        <div>
                            <label for="bairro">Bairro:</label>
                            <input type="text" name="bairro" id="bairro" placeholder="Malhangalene">
                        </div>
                    </div>
                    <div class="buttons">
                        <button type="button" class="btn next">Próximo</button>
                    </div>
                </div>

                <!-- Etapa 2 -->
                <div class="form-step" id="step2">
                    <div class="input-group">
                        <div>
                            <label for="postoAdministrativo">Posto Administrativo:</label>
                            <input type="text" name="postoAdministrativo" id="postoAdministrativo" placeholder="Kampfumo">
                        </div>
                        <div>
                            <label for="localidade">Localidade:</label>
                            <input type="text" name="localidade" id="localidade" placeholder="xxxxx">
                        </div>
                        <div>
                            <label for="avenida">Avenida:</label>
                            <input type="text" name="avenida" id="avenida" placeholder="Av. Acordos de Lusaka">
                        </div>
                    </div>
                    <div class="buttons">
                        <button type="button" class="btn prev">Voltar</button>
                        <button type="button" class="btn next">Próximo</button>
                    </div>
                </div>

                <!-- Etapa 3 -->
                <div class="form-step" id="step3">
                    <div class="input-group">
                        <div>
                            <label for="codigoPostal">Código Postal:</label>
                            <input type="text" name="codigoPostal" id="codigoPostal" placeholder="1101">
                        </div>
                        <div>
                            <label for="latitude">Latitude:</label>
                            <input type="text" name="latitude" id="latitude" placeholder="Latitude">
                        </div>
                        <div>
                            <label for="longitude">Longitude:</label>
                            <input type="text" name="longitude" id="longitude" placeholder="Longitude">
                        </div>
                        <div>
                            <label for="referencia">Referência:</label>
                            <input type="text" name="referencia" id="referencia" placeholder="Perto do mercado...">
                        </div>
                    </div>
                    <div class="buttons">
                        <button type="button" class="btn prev">Voltar</button>
                        <button type="submit" class="btn">Cadastrar</button>
                    </div>
                </div>
            </form>
        </div>

        <footer>
            &copy; <?php echo date("Y"); ?> Marktour - Todos os direitos reservados.
        </footer>
    </main>

    <script>
        // Controle das etapas
        const steps = document.querySelectorAll(".form-step");
        const nextBtns = document.querySelectorAll(".next");
        const prevBtns = document.querySelectorAll(".prev");
        let currentStep = 0;

        nextBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                if (validateStep(currentStep)) {
                    changeStep(1);
                }
            });
        });

        prevBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                changeStep(-1);
            });
        });

        function changeStep(direction) {
            steps[currentStep].classList.remove("active");
            currentStep += direction;
            steps[currentStep].classList.add("active");
        }

        // Validação simples
        function validateStep(step) {
            let valid = true;
            if (step === 0) {
                const provincia = document.getElementById("provincia");
                const distrito = document.getElementById("distrito");

                document.getElementById("error-provincia").textContent = "";
                document.getElementById("error-distrito").textContent = "";

                if (provincia.value.trim().length < 2) {
                    document.getElementById("error-provincia").textContent = "Informe uma província válida.";
                    valid = false;
                }
                if (distrito.value.trim().length < 2) {
                    document.getElementById("error-distrito").textContent = "Informe um distrito válido.";
                    valid = false;
                }
            }
            return valid;
        }
    </script>
</body>

</html>