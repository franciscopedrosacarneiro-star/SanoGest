<?php

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/database.php';

redirect_if_not_logged();

$id_localizacao = $_GET['id_localizacao'] ?? null;

if (!validar_id($id_localizacao)) {
    redirecionar('localizacoes.php');
}

$localizacao = buscar_localizacao_por_id($pdo, $id_localizacao);

if (!$localizacao) {
    redirecionar('localizacoes.php');
}

$equipamentos = buscar_equipamentos_da_localizacao($pdo, $id_localizacao);

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Consultar Localização</title>

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

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="text-primary fw-bold mb-1">
                        <i class="fa-solid fa-circle-info me-2"></i>Detalhes da Localização
                    </h2>

                    <p class="text-muted mb-0">
                        Consulta da localização física e dos equipamentos associados.
                    </p>
                </div>

                <div>
                    <a href="localizacoes.php" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                    </a>

                    <?php if (!localizacao_inativa($localizacao)): ?>
                        <a href="editar.php?id_localizacao=<?= htmlspecialchars($localizacao->id_localizacao) ?>" class="btn btn-warning text-white">
                            <i class="fa-solid fa-pen me-2"></i>Editar
                        </a>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled>
                            <i class="fa-solid fa-ban me-2"></i>Localização Inativa
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="alert alert-light border mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">
                            <?= htmlspecialchars($localizacao->edificio ?? '') ?> —
                            Piso <?= htmlspecialchars($localizacao->piso ?? '') ?> —
                            <?= htmlspecialchars($localizacao->servico ?? '') ?>
                        </h4>

                        <span class="text-muted">
                            Sala/Gabinete: <?= htmlspecialchars($localizacao->sala ?? '—') ?>
                        </span>
                    </div>

                    <div>
                        <span class="badge bg-<?= badge_estado_localizacao($localizacao->estado ?? '') ?> fs-6">
                            <?= htmlspecialchars($localizacao->estado ?? '') ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-light fw-bold">
                            <i class="fa-solid fa-map-location-dot me-2 text-primary"></i>
                            Informações do Espaço
                        </div>

                        <div class="card-body">
                            <p>
                                <strong>Edifício:</strong>
                                <?= htmlspecialchars($localizacao->edificio ?? '—') ?>
                            </p>

                            <p>
                                <strong>Piso:</strong>
                                Piso <?= htmlspecialchars($localizacao->piso ?? '—') ?>
                            </p>

                            <p>
                                <strong>Serviço/Departamento:</strong>
                                <?= htmlspecialchars($localizacao->servico ?? '—') ?>
                            </p>

                            <p>
                                <strong>Sala/Gabinete:</strong>
                                <?= htmlspecialchars($localizacao->sala ?? '—') ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-light fw-bold">
                            <i class="fa-solid fa-hospital me-2 text-primary"></i>
                            Caracterização
                        </div>

                        <div class="card-body">
                            <p>
                                <strong>Tipo de Área:</strong>
                                <span class="badge bg-<?= badge_tipo_area_localizacao($localizacao->tipo_area ?? '') ?>">
                                    <?= htmlspecialchars($localizacao->tipo_area ?? '—') ?>
                                </span>
                            </p>

                            <p>
                                <strong>Estado:</strong>
                                <span class="badge bg-<?= badge_estado_localizacao($localizacao->estado ?? '') ?>">
                                    <?= htmlspecialchars($localizacao->estado ?? '—') ?>
                                </span>
                            </p>

                            <p>
                                <strong>Capacidade de Equipamentos:</strong>
                                <?= htmlspecialchars($localizacao->capacidade_equipamentos ?? 0) ?>
                            </p>

                            <p>
                                <strong>Equipamentos Associados:</strong>
                                <?= htmlspecialchars($localizacao->total_equipamentos_associados ?? 0) ?>
                            </p>

                            <p>
                                <strong>Acesso Restrito:</strong>
                                <?= htmlspecialchars($localizacao->acesso_restrito ?? '—') ?>
                            </p>

                            <p>
                                <strong>Prioridade Técnica:</strong>
                                <span class="badge bg-<?= badge_prioridade_localizacao($localizacao->prioridade_tecnica ?? '') ?>">
                                    <?= htmlspecialchars($localizacao->prioridade_tecnica ?? '—') ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card mt-4 shadow-sm border-0">
                <div class="card-header bg-light fw-bold">
                    <i class="fa-solid fa-clipboard me-2 text-primary"></i>
                    Observações
                </div>

                <div class="card-body">
                    <?= !empty($localizacao->observacoes) ? nl2br(htmlspecialchars($localizacao->observacoes)) : 'Sem observações registadas.' ?>
                </div>
            </div>

            <div class="card mt-4 shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="fw-bold">
                        <i class="fa-solid fa-microchip me-2 text-primary"></i>
                        Equipamentos nesta Localização
                    </span>

                    <span class="badge bg-secondary">
                        <?= count($equipamentos) ?> equipamento(s)
                    </span>
                </div>

                <div class="card-body p-0">
                    <?php if (empty($equipamentos)): ?>
                        <div class="p-4 text-center text-muted">
                            <i class="fa-solid fa-circle-info me-2"></i>
                            Não existem equipamentos associados a esta localização.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Designação</th>
                                        <th>Marca/Modelo</th>
                                        <th>Estado</th>
                                        <th>Criticidade</th>
                                        <th class="text-end">Ação</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($equipamentos as $equipamento): ?>
                                        <tr>
                                            <td>
                                                <?= htmlspecialchars($equipamento->codigo_inventario ?? '') ?>
                                            </td>

                                            <td class="fw-bold">
                                                <?= htmlspecialchars($equipamento->designacao ?? '') ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars(($equipamento->marca ?? '') . ' ' . ($equipamento->modelo ?? '')) ?>
                                            </td>

                                            <td>
                                                <span class="badge bg-<?= badge_estado_equipamento($equipamento->estado ?? '') ?>">
                                                    <?= htmlspecialchars($equipamento->estado ?? '') ?>
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge bg-<?= badge_criticidade_equipamento($equipamento->criticidade ?? '') ?>">
                                                    <?= htmlspecialchars($equipamento->criticidade ?? '') ?>
                                                </span>
                                            </td>

                                            <td class="text-end">
                                                <a href="../equipamentos/consultar.php?id_equipamento=<?= htmlspecialchars($equipamento->id_equipamento) ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    Ver
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>
</div>

<script src="../../../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>