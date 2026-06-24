
<?php

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/database.php';

redirect_if_not_logged();

$localizacoes = listar_localizacoes($pdo);
$estatisticas = calcular_estatisticas_localizacoes($localizacoes);

$totalLocalizacoes = $estatisticas['total'];
$totalAtivas = $estatisticas['ativas'];
$totalInativas = $estatisticas['inativas'];
$totalEquipamentosAssociados = $estatisticas['equipamentos_associados'];

$edificios = [];
$pisos = [];
$servicos = [];

foreach ($localizacoes as $localizacao) {
    if (!empty($localizacao->edificio)) {
        $edificios[] = $localizacao->edificio;
    }

    if ($localizacao->piso !== null && $localizacao->piso !== '') {
        $pisos[] = 'Piso ' . $localizacao->piso;
    }

    if (!empty($localizacao->servico)) {
        $servicos[] = $localizacao->servico;
    }
}

$edificios = array_unique($edificios);
$pisos = array_unique($pisos);
$servicos = array_unique($servicos);

sort($edificios);
sort($pisos);
sort($servicos);

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Localizações</title>

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

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="text-primary fw-bold mb-1">
                        <i class="fa-solid fa-map-location-dot me-2"></i>Localizações
                    </h2>

                    <p class="text-muted mb-0">
                        Gestão das localizações físicas dos equipamentos médicos por edifício, piso, serviço e sala.
                    </p>
                </div>

                <a href="novo.php" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-2"></i>Nova Localização
                </a>
            </div>

            <!-- Cartões de resumo -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card p-3 shadow-sm border-0 border-start border-primary border-5 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total</h6>
                                <h3 class="fw-bold mb-0"><?= htmlspecialchars($totalLocalizacoes) ?></h3>
                            </div>
                            <i class="fa-solid fa-map-location-dot text-primary fs-2"></i>
                        </div>
                        <small class="text-muted mt-2">Localizações registadas</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3 shadow-sm border-0 border-start border-success border-5 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Ativas</h6>
                                <h3 class="fw-bold mb-0"><?= htmlspecialchars($totalAtivas) ?></h3>
                            </div>
                            <i class="fa-solid fa-circle-check text-success fs-2"></i>
                        </div>
                        <small class="text-muted mt-2">Localizações disponíveis</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3 shadow-sm border-0 border-start border-secondary border-5 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Inativas</h6>
                                <h3 class="fw-bold mb-0"><?= htmlspecialchars($totalInativas) ?></h3>
                            </div>
                            <i class="fa-solid fa-ban text-secondary fs-2"></i>
                        </div>
                        <small class="text-muted mt-2">Localizações inativas</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3 shadow-sm border-0 border-start border-info border-5 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Equipamentos</h6>
                                <h3 class="fw-bold mb-0"><?= htmlspecialchars($totalEquipamentosAssociados) ?></h3>
                            </div>
                            <i class="fa-solid fa-microchip text-info fs-2"></i>
                        </div>
                        <small class="text-muted mt-2">Equipamentos associados</small>
                    </div>
                </div>
            </div>

            <!-- Pesquisa e filtros -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold">
                    <i class="fa-solid fa-filter me-2 text-primary"></i>Pesquisa e Filtros
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-5">
                            <label for="pesquisaLocalizacao" class="form-label fw-bold">Pesquisar</label>
                            <input 
                                type="text" 
                                id="pesquisaLocalizacao" 
                                class="form-control" 
                                placeholder="Pesquisar por edifício, piso, serviço ou sala..."
                            >
                        </div>

                        <div class="col-md-3">
                            <label for="filtroEdificio" class="form-label fw-bold">Edifício</label>
                            <select id="filtroEdificio" class="form-select">
                                <option value="">Todos</option>

                                <?php foreach ($edificios as $edificio): ?>
                                    <option value="<?= htmlspecialchars($edificio) ?>">
                                        <?= htmlspecialchars($edificio) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="filtroPiso" class="form-label fw-bold">Piso</label>
                            <select id="filtroPiso" class="form-select">
                                <option value="">Todos</option>

                                <?php foreach ($pisos as $piso): ?>
                                    <option value="<?= htmlspecialchars($piso) ?>">
                                        <?= htmlspecialchars($piso) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="filtroServico" class="form-label fw-bold">Serviço</label>
                            <select id="filtroServico" class="form-select">
                                <option value="">Todos</option>

                                <?php foreach ($servicos as $servico): ?>
                                    <option value="<?= htmlspecialchars($servico) ?>">
                                        <?= htmlspecialchars($servico) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Tabela -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold">
                        <i class="fa-solid fa-list me-2 text-primary"></i>Lista de Localizações
                    </span>

                    <small class="text-muted">
                        Localização física atual dos equipamentos hospitalares
                    </small>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tabelaLocalizacoes">
                        <thead class="table-light">
                            <tr>
                                <th>Edifício</th>
                                <th>Piso</th>
                                <th>Serviço</th>
                                <th>Sala/Gabinete</th>
                                <th>Tipo de Área</th>
                                <th>Equipamentos</th>
                                <th>Estado</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($localizacoes)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fa-solid fa-circle-info me-2"></i>
                                        Não existem localizações registadas.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($localizacoes as $localizacao): ?>
                                    <tr 
                                        data-edificio="<?= htmlspecialchars($localizacao->edificio ?? '') ?>" 
                                        data-piso="Piso <?= htmlspecialchars($localizacao->piso ?? '') ?>" 
                                        data-servico="<?= htmlspecialchars($localizacao->servico ?? '') ?>"
                                    >
                                        <td class="fw-bold">
                                            <?= htmlspecialchars($localizacao->edificio ?? '') ?>
                                        </td>

                                        <td>
                                            Piso <?= htmlspecialchars($localizacao->piso ?? '') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($localizacao->servico ?? '') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($localizacao->sala ?? '') ?>
                                        </td>

                                        <td>
                                            <span class="badge bg-<?= badge_tipo_area_localizacao($localizacao->tipo_area ?? '') ?>">
                                                <?= htmlspecialchars($localizacao->tipo_area ?? '') ?>
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge bg-secondary">
                                                <?= htmlspecialchars($localizacao->total_equipamentos_associados ?? 0) ?> Equipamentos
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge bg-<?= badge_estado_localizacao($localizacao->estado ?? '') ?>">
                                                <?= htmlspecialchars($localizacao->estado ?? '') ?>
                                            </span>
                                        </td>

                                        <td class="text-end">

                                            <a href="consultar.php?id_localizacao=<?= htmlspecialchars($localizacao->id_localizacao) ?>" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Consultar">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <?php if (!localizacao_inativa($localizacao)): ?>
                                                <a href="editar.php?id_localizacao=<?= htmlspecialchars($localizacao->id_localizacao) ?>" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Editar">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>

                                                <a href="apagar.php?id_localizacao=<?= htmlspecialchars($localizacao->id_localizacao) ?>" 
                                                   class="btn btn-sm btn-outline-danger" 
                                                   title="Inativar">
                                                    <i class="fa-solid fa-ban"></i>
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-secondary" disabled title="Localização inativa">
                                                    <i class="fa-solid fa-pen-slash"></i>
                                                </button>

                                                <button class="btn btn-sm btn-outline-secondary" disabled title="Já se encontra inativa">
                                                    <i class="fa-solid fa-ban"></i>
                                                </button>
                                            <?php endif; ?>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white text-muted small">
                    Use a pesquisa ou os filtros para localizar rapidamente localizações por edifício, piso, serviço ou sala.
                </div>
            </div>

        </div>
    </main>
</div>

<script src="../../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../../assets/js/1240881.js"></script>

</body>
</html>
