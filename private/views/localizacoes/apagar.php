<?php

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/database.php';

redirect_if_not_logged();

$id_localizacao = $_GET['id_localizacao'] ?? $_POST['id_localizacao'] ?? null;

if (!validar_id($id_localizacao)) {
    redirecionar('localizacoes.php');
}

$localizacao = buscar_localizacao_por_id($pdo, $id_localizacao);

if (!$localizacao) {
    redirecionar('localizacoes.php');
}

if (localizacao_inativa($localizacao)) {
    redirecionar('consultar.php?id_localizacao=' . $id_localizacao);
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        inativar_localizacao($pdo, $id_localizacao);
        redirecionar('consultar.php?id_localizacao=' . $id_localizacao);
    } catch (PDOException $e) {
        $erro = 'Não foi possível inativar a localização.';
    }
}

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Inativar Localização</title>

    <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/css/1240881.css">
    <link rel="stylesheet" href="../../../assets/fontawesome/all.min.css">
    <link rel="shortcut icon" href="../../../assets/img/logo 125.png" type="image/png">
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top" style="z-index: 1030 !important; padding-left: 0px !important; padding-right: 0px !important;">
    <div style="width: 100% !important; display: flex !important; align-items: center !important; justify-content: space-between !important; padding-left: 5px !important; padding-right: 20px !important;">
        
        <a class="navbar-brand fw-bold" href="../../../private/index.php" style="margin-left: 10px !important; display: flex !important; align-items: center !important;">
            <img src="../../../assets/img/logo 255.png" alt="Logo SanoGest" style="height: 35px;" class="me-2">
            SanoGest Admin
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPrivada">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navPrivada">
            <ul class="navbar-nav ms-auto">
                
                <li class="nav-item dropdown">
                    <a class="btn btn-primary dropdown-toggle text-white fw-bold px-3" 
                       href="#" 
                       id="menuUtilizador" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false">
                        <i class="fa-solid fa-user me-2"></i>Utilizador
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="menuUtilizador">
                        <li>
                            <a class="dropdown-item py-2" href="../../../private/views/perfil/alterar-senha.php">
                                <i class="fa-solid fa-key me-2 text-muted"></i>Trocar palavra-passe
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item py-2 text-danger" href="../../../private/logout.php">
                                <i class="fa-solid fa-right-from-bracket me-2"></i>Sair
                            </a>
                        </li>
                    </ul>
                </li>
                
            </ul>
        </div>
        
    </div>
</nav>

<div>
    <nav class="bg-white border-end shadow-sm sidebar-fixa">
        <div class="p-3">
            <ul class="nav nav-pills flex-column mb-auto">
                
                <li>
                    <a href="../localizacoes/localizacoes.php" class="nav-link text-dark">
                        <i class="fa-solid fa-map-location-dot me-2"></i>Localizações
                    </a>
                </li>

            </ul>
        </div>
    </nav>

    <main class="p-5 fundo-dashboard conteudo-principal">
        <div class="bg-white bg-opacity-75 p-5 rounded shadow-sm">

            <div class="text-center">

                <div class="text-warning mb-3">
                    <i class="fa-solid fa-triangle-exclamation fa-4x"></i>
                </div>

                <h2 class="text-warning fw-bold mb-3">
                    Confirmar Inativação
                </h2>

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger text-start">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        <?= htmlspecialchars($erro) ?>
                    </div>
                <?php endif; ?>

                <div class="alert alert-light border text-start mx-auto" style="max-width: 800px;">
                    <h5 class="fw-bold text-primary mb-3">
                        <?= htmlspecialchars($localizacao->edificio ?? '') ?> |
                        Piso <?= htmlspecialchars($localizacao->piso ?? '') ?> |
                        <?= htmlspecialchars($localizacao->servico ?? '') ?>
                    </h5>

                    <p class="mb-2">
                        <strong>Sala/Gabinete:</strong>
                        <?= htmlspecialchars($localizacao->sala ?? '—') ?>
                    </p>

                    <p class="mb-2">
                        <strong>Tipo de área:</strong>
                        <span class="badge bg-<?= badge_tipo_area_localizacao($localizacao->tipo_area ?? '') ?>">
                            <?= htmlspecialchars($localizacao->tipo_area ?? '—') ?>
                        </span>
                    </p>

                    <p class="mb-2">
                        <strong>Estado atual:</strong>
                        <span class="badge bg-<?= badge_estado_localizacao($localizacao->estado ?? '') ?>">
                            <?= htmlspecialchars($localizacao->estado ?? '—') ?>
                        </span>
                    </p>

                    <p class="mb-0">
                        <strong>Equipamentos associados:</strong>
                        <?= htmlspecialchars($localizacao->total_equipamentos_associados ?? 0) ?>
                    </p>
                </div>

                <p class="mb-4">
                    Tem a certeza que pretende inativar esta localização?<br>
                    A localização deixará de poder ser editada, mas continuará disponível para consulta.
                </p>

                <?php if (($localizacao->total_equipamentos_associados ?? 0) > 0): ?>
                    <div class="alert alert-warning text-start mx-auto" style="max-width: 800px;">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        Esta localização tem equipamentos associados. Ao inativar, os equipamentos continuam associados a ela, mas a localização ficará marcada como <strong>Inativa</strong>.
                    </div>
                <?php endif; ?>

                <form action="apagar.php?id_localizacao=<?= htmlspecialchars($id_localizacao) ?>" method="POST">
                    <input type="hidden" name="id_localizacao" value="<?= htmlspecialchars($id_localizacao) ?>">

                    <div class="d-flex justify-content-center gap-3">
                        <a href="consultar.php?id_localizacao=<?= htmlspecialchars($id_localizacao) ?>" class="btn btn-secondary px-4">
                            Cancelar
                        </a>

                        <button type="submit" class="btn btn-warning text-white px-4">
                            <i class="fa-solid fa-ban me-2"></i>Sim, Inativar Localização
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </main>
</div>

<script src="../../../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>