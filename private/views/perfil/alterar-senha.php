<?php

require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Alterar Palavra-passe - SanoGest</title>

    <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/css/1240881.css">
    <link rel="stylesheet" href="../../../assets/fontawesome/all.min.css">
    <link rel="shortcut icon" href="../../../assets/img/logo 125.png" type="image/png">
</head>

<body class="bg-light d-flex align-items-center min-vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">

                <div class="card p-4 shadow-sm border-0">

                    <div class="text-center mb-4">
                        <img src="../../../assets/img/logo 255.png" alt="Logo" style="height: 60px;" class="mb-3">
                        <h3 class="text-primary fw-bold">SanoGest</h3>
                        <p class="text-muted small mb-0">Alterar Palavra-passe</p>
                    </div>

                    <form id="formAlterarSenha" action="../../../private/index.php" method="post">

                        <div class="mb-3">
                            <label for="senhaAtual" class="form-label fw-bold">Palavra-passe atual</label>
                            <input 
                                type="password" 
                                id="senhaAtual" 
                                name="senha_atual"
                                class="form-control" 
                                placeholder="Palavra-passe atual"
                                minlength="6"
                                maxlength="12"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="novaSenha" class="form-label fw-bold">Nova palavra-passe</label>
                            <input 
                                type="password" 
                                id="novaSenha" 
                                name="nova_senha"
                                class="form-control" 
                                placeholder="6 a 12 caracteres"
                                minlength="6"
                                maxlength="12"
                                required
                            >
                            <div class="form-text">
                                A palavra-passe deve ter entre 6 e 12 caracteres.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="confirmarSenha" class="form-label fw-bold">Confirmar nova palavra-passe</label>
                            <input 
                                type="password" 
                                id="confirmarSenha" 
                                name="confirmar_senha"
                                class="form-control" 
                                placeholder="Repita a nova palavra-passe"
                                minlength="6"
                                maxlength="12"
                                required
                            >
                            <div id="mensagemSenha" class="form-text"></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                            Guardar Alteração
                        </button>

                    </form>

                    <div class="text-center mt-3">
                        <a href="../../../private/index.html" class="text-decoration-none text-muted small">
                            ← Voltar ao Painel
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script src="../../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/1240881.js"></script>

</body>
</html>