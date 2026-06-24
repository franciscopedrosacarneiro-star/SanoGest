<?php

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/database.php';

redirect_if_not_logged();

$garantias = listar_garantias($pdo);
$estatisticas = calcular_estatisticas_garantias($garantias);

$totalGarantias = $estatisticas['total'];
$totalAtivas = $estatisticas['ativas'];
$totalATerminar = $estatisticas['a_terminar'];
$totalExpiradas = $estatisticas['expiradas'];

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Garantias e Contratos</title>

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
                        <i class="fa-solid fa-shield-halved me-2"></i>Garantias e Contratos
                    </h2>

                    <p class="text-muted mb-0">
                        Controlo de garantias, contratos de manutenção e datas de validade dos equipamentos médicos.
                    </p>
                </div>

                <a href="novo.php" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-2"></i>Registar Garantia/Contrato
                </a>
            </div>

            <!-- Cartões de resumo -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card p-3 shadow-sm border-0 border-start border-primary border-5 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total</h6>
                                <h3 class="fw-bold mb-0"><?= htmlspecialchars($totalGarantias) ?></h3>
                            </div>
                            <i class="fa-solid fa-file-contract text-primary fs-2"></i>
                        </div>
                        <small class="text-muted mt-2">Garantias e contratos registados</small>
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
                        <small class="text-muted mt-2">Dentro da validade</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3 shadow-sm border-0 border-start border-warning border-5 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">A terminar</h6>
                                <h3 class="fw-bold mb-0"><?= htmlspecialchars($totalATerminar) ?></h3>
                            </div>
                            <i class="fa-solid fa-clock text-warning fs-2"></i>
                        </div>
                        <small class="text-muted mt-2">Necessitam acompanhamento</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3 shadow-sm border-0 border-start border-danger border-5 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Expiradas</h6>
                                <h3 class="fw-bold mb-0"><?= htmlspecialchars($totalExpiradas) ?></h3>
                            </div>
                            <i class="fa-solid fa-calendar-xmark text-danger fs-2"></i>
                        </div>
                        <small class="text-muted mt-2">Requerem revisão</small>
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
                            <label for="pesquisaGarantia" class="form-label fw-bold">Pesquisar</label>
                            <input 
                                type="text" 
                                id="pesquisaGarantia" 
                                class="form-control" 
                                placeholder="Pesquisar por equipamento, fornecedor, contrato ou responsável..."
                            >
                        </div>

                        <div class="col-md-3">
                            <label for="filtroEstadoGarantia" class="form-label fw-bold">Estado</label>
                            <select id="filtroEstadoGarantia" class="form-select">
                                <option value="">Todos</option>
                                <option value="Ativa">Ativa</option>
                                <option value="A Terminar">A terminar</option>
                                <option value="Expirada">Expirada</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="filtroTipoContrato" class="form-label fw-bold">Tipo</label>
                            <select id="filtroTipoContrato" class="form-select">
                                <option value="">Todos</option>
                                <option value="Garantia">Garantia</option>
                                <option value="Preventiva">Preventiva</option>
                                <option value="Corretiva">Corretiva</option>
                                <option value="Full Service">Full Service</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="filtroCriticidadeGarantia" class="form-label fw-bold">Criticidade</label>
                            <select id="filtroCriticidadeGarantia" class="form-select">
                                <option value="">Todas</option>
                                <option value="Baixa">Baixa</option>
                                <option value="Média">Média</option>
                                <option value="Alta">Alta</option>
                                <option value="Suporte de Vida">Suporte de Vida</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Tabela -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold">
                        <i class="fa-solid fa-list me-2 text-primary"></i>Lista de Garantias e Contratos
                    </span>

                    <small class="text-muted">
                        Controlo documental e contratual dos equipamentos
                    </small>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tabelaGarantias">
                        <thead class="table-light">
                            <tr>
                                <th>Equipamento</th>
                                <th>Fornecedor/Responsável</th>
                                <th>Tipo</th>
                                <th>Início</th>
                                <th>Fim</th>
                                <th>Estado</th>
                                <th>Criticidade</th>
                                <th>Documento</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($garantias)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fa-solid fa-circle-info me-2"></i>
                                        Não existem garantias ou contratos registados.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($garantias as $garantia): ?>
                                    <tr 
                                        data-estado="<?= htmlspecialchars($garantia->estado ?? '') ?>" 
                                        data-tipo="<?= htmlspecialchars($garantia->tipo_contrato ?? '') ?>" 
                                        data-criticidade="<?= htmlspecialchars($garantia->criticidade ?? '') ?>"
                                    >
                                        <td class="fw-bold">
                                            <?= htmlspecialchars(($garantia->codigo_inventario ?? '') . ' | ' . ($garantia->equipamento_designacao ?? '')) ?>
                                        </td>

                                        <td>
                                            <?= !empty($garantia->fornecedor_nome) ? htmlspecialchars($garantia->fornecedor_nome) : 'Sem fornecedor associado' ?>
                                        </td>

                                        <td>
                                            <span class="badge bg-<?= badge_tipo_contrato($garantia->tipo_contrato ?? '') ?>">
                                                <?= htmlspecialchars($garantia->tipo_contrato ?? '') ?>
                                            </span>
                                        </td>

                                        <td>
                                            <?= !empty($garantia->data_inicio) ? htmlspecialchars(date('d/m/Y', strtotime($garantia->data_inicio))) : '—' ?>
                                        </td>

                                        <td>
                                            <?= !empty($garantia->data_fim) ? htmlspecialchars(date('d/m/Y', strtotime($garantia->data_fim))) : '—' ?>
                                        </td>

                                        <td>
                                            <span class="badge bg-<?= badge_estado_garantia($garantia->estado ?? '') ?>">
                                                <?= htmlspecialchars($garantia->estado ?? '') ?>
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge bg-<?= badge_criticidade_garantia($garantia->criticidade ?? '') ?>">
                                                <?= htmlspecialchars($garantia->criticidade ?? '') ?>
                                            </span>
                                        </td>

                                        <td>
                                            <?= !empty($garantia->documento_associado) ? htmlspecialchars($garantia->documento_associado) : '—' ?>
                                        </td>

                                        <td class="text-end">

                                            <a href="consultar.php?id_garantia=<?= htmlspecialchars($garantia->id_garantia) ?>" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Consultar">
                                                <i class="fa-solid fa-file-lines"></i>
                                            </a>

                                            <?php if (!garantia_expirada($garantia)): ?>
                                                <a href="editar.php?id_garantia=<?= htmlspecialchars($garantia->id_garantia) ?>" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Editar">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>

                                                <a href="apagar.php?id_garantia=<?= htmlspecialchars($garantia->id_garantia) ?>" 
                                                   class="btn btn-sm btn-outline-danger" 
                                                   title="Terminar/Expirar">
                                                    <i class="fa-solid fa-ban"></i>
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-secondary" disabled title="Garantia expirada">
                                                    <i class="fa-solid fa-pen-slash"></i>
                                                </button>

                                                <button class="btn btn-sm btn-outline-secondary" disabled title="Já se encontra expirada">
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
                    Use a pesquisa ou os filtros para localizar rapidamente garantias por equipamento, fornecedor, estado, tipo ou criticidade.
                </div>
            </div>

        </div>
    </main>
</div>

<script src="../../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../../assets/js/1240881.js"></script>

</body>
</html>