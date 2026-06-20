
<?php

require_once __DIR__ . '/../private/includes/database.php';
require_once __DIR__ . '/../private/includes/funcoes.php';

redirect_if_logged();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = limpar_texto($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $erro = 'Preenche o email e a palavra-passe.';
    } else {
        $sql = "SELECT * FROM utilizadores WHERE email = :email AND estado = 'Ativo' LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $utilizador = $stmt->fetch();

        if ($utilizador && password_verify($password, $utilizador->password_hash)) {
            start_session();

            $_SESSION['utilizador'] = [
                'id' => $utilizador->id_utilizador,
                'nome' => $utilizador->nome,
                'email' => $utilizador->email,
                'tipo' => $utilizador->tipo
            ];

            header('Location: ' . BASE_URL . '/private/index.php');
            exit;
        } else {
            $erro = 'Email ou palavra-passe incorretos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login - SanoGest</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/1240881.css">
    <link rel="shortcut icon" href="../assets/img/logo 125.png" type="image/png">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card p-4 shadow-sm border-0">
                    
                    <div class="text-center mb-4">
                        <img src="../assets/img/logo 255.png" alt="Logo" style="height: 60px;" class="mb-3">
                        <h3 class="text-primary fw-bold">SanoGest</h3>
                        <p class="text-muted small">Área de Engenharia Clínica</p>
                    </div>

                    <form action="#" method="post" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Utilizador (Email)</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="6" maxlength="12"> <div class="form-text"> A palavra-passe deve ter entre 6 e 12 caracteres. </div>
                        </div>
<?php if (!empty($erro)): ?>
    <div class="alert alert-danger">
        <i class="fa-solid fa-triangle-exclamation me-2"></i>
        <?= $erro ?>
    </div>
<?php endif; ?>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                            Entrar
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="index.html" class="text-decoration-none text-muted small">
                            ← Voltar ao Ínicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>