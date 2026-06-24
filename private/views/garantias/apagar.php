<?php

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/database.php';

redirect_if_not_logged();

$id_garantia = $_GET['id_garantia'] ?? $_POST['id_garantia'] ?? null;

if (!validar_id($id_garantia)) {
    redirecionar('garantias.php');
}

$garantia = buscar_garantia_por_id($pdo, $id_garantia);

if (!$garantia) {
    redirecionar('garantias.php');
}

if (garantia_expirada($garantia)) {
    redirecionar('consultar.php?id_garantia=' . $id_garantia);
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        terminar_garantia($pdo, $id_garantia);
        redirecionar('consultar.php?id_garantia=' . $id_garantia);
    } catch (PDOException $e) {
        $erro = 'Não foi possível terminar/expirar a garantia.';
    }
}

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Terminar Garantia</title>

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
                    <a href="../dashboard/dashboard.php" class="nav-link text-dark">
                        <i class="fa-solid fa-gauge me-2"></i>Dashboard
                    </a>
                </li>

                <li>
                    <a href="../equipamentos/equipamentos.php" class="nav-link text-dark">
                        <i class="fa-solid fa-microchip me-2"></i>Equipamentos
                    </a>
                </li>

                <li>
                    <a href="../fornecedores/fornecedores.php" class="nav-link text-dark">
                        <i class="fa-solid fa-truck-medical me-2"></i>Fornecedores
                    </a>
                </li>

                <li>
                    <a href="../localizacoes/localizacoes.php" class="nav-link text-dark">
                        <i class="fa-solid fa-map-location-dot me-2"></i>Localizações
                    </a>
                </li>

                <li>
                    <a href="../garantias/garantias.php" class="nav-link text-dark">
                        <i class="fa-solid fa-shield-halved me-2"></i>Garantias
                    </a>
                </li>

                <li>
                    <a href="../documentacao/documentacao.php" class="nav-link text-dark">
                        <i class="fa-solid fa-file-contract me-2"></i>Documentação
                    </a>
                </li>

                <li>
                    <a href="../editar/editar-index.php" class="nav-link active">
                        <i class="fa-solid fa-pen-to-square me-2"></i>Editar Página Pública
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
                    Confirmar Término da Garantia
                </h2>

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger text-start">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        <?= htmlspecialchars($erro) ?>
                    </div>
                <?php endif; ?>

                <div class="alert alert-light border text-start mx-auto" style="max-width: 850px;">
                    <h5 class="fw-bold text-primary mb-3">
                        <?= htmlspecialchars($garantia->codigo_inventario ?? '') ?> |
                        <?= htmlspecialchars($garantia->equipamento_designacao ?? '') ?>
                    </h5>

                    <p class="mb-2">
                        <strong>Tipo:</strong>
                        <span class="badge bg-<?= badge_tipo_contrato($garantia->tipo_contrato ?? '') ?>">
                            <?= htmlspecialchars($garantia->tipo_contrato ?? '—') ?>
                        </span>
                    </p>

                    <p class="mb-2">
                        <strong>Fornecedor:</strong>
                        <?= !empty($garantia->fornecedor_nome) ? htmlspecialchars($garantia->fornecedor_nome) : 'Sem fornecedor associado' ?>
                    </p>

                    <p class="mb-2">
                        <strong>N.º contrato:</strong>
                        <?= htmlspecialchars($garantia->numero_contrato ?? '—') ?>
                    </p>

                    <p class="mb-2">
                        <strong>Data de início:</strong>
                        <?= !empty($garantia->data_inicio) ? htmlspecialchars(date('d/m/Y', strtotime($garantia->data_inicio))) : '—' ?>
                    </p>

                    <p class="mb-2">
                        <strong>Data de fim:</strong>
                        <?= !empty($garantia->data_fim) ? htmlspecialchars(date('d/m/Y', strtotime($garantia->data_fim))) : '—' ?>
                    </p>

                    <p class="mb-0">
                        <strong>Estado atual:</strong>
                        <span class="badge bg-<?= badge_estado_garantia($garantia->estado ?? '') ?>">
                            <?= htmlspecialchars($garantia->estado ?? '—') ?>
                        </span>
                    </p>
                </div>

                <div class="alert alert-warning text-start mx-auto" style="max-width: 850px;">
                    <i class="fa-solid fa-circle-info me-2"></i>
                    Esta ação não remove a garantia da base de dados. Apenas muda o estado para 
                    <strong>Expirada</strong>, mantendo o registo disponível para consulta.
                </div>

                <p class="mb-4">
                    Tem a certeza que pretende terminar esta garantia ou contrato?
                </p>

                <form action="apagar.php?id_garantia=<?= htmlspecialchars($id_garantia) ?>" method="POST">
                    <input type="hidden" name="id_garantia" value="<?= htmlspecialchars($id_garantia) ?>">

                    <div class="d-flex justify-content-center gap-3">
                        <a href="consultar.php?id_garantia=<?= htmlspecialchars($id_garantia) ?>" class="btn btn-secondary px-4">
                            Cancelar
                        </a>

                        <button type="submit" class="btn btn-warning text-white px-4">
                            <i class="fa-solid fa-ban me-2"></i>Sim, Terminar Garantia
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