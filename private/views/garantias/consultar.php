<?php

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/database.php';

redirect_if_not_logged();

$id_garantia = $_GET['id_garantia'] ?? null;

if (!validar_id($id_garantia)) {
    redirecionar('garantias.php');
}

$garantia = buscar_garantia_por_id($pdo, $id_garantia);

if (!$garantia) {
    redirecionar('garantias.php');
}

$duracao = '—';

if (!empty($garantia->data_inicio) && !empty($garantia->data_fim)) {
    try {
        $dataInicio = new DateTime($garantia->data_inicio);
        $dataFim = new DateTime($garantia->data_fim);
        $diferenca = $dataInicio->diff($dataFim);

        $meses = ($diferenca->y * 12) + $diferenca->m;

        if ($meses > 0 && $diferenca->d > 0) {
            $duracao = $meses . ' meses e ' . $diferenca->d . ' dias';
        } elseif ($meses > 0) {
            $duracao = $meses . ' meses';
        } else {
            $duracao = $diferenca->d . ' dias';
        }
    } catch (Exception $e) {
        $duracao = '—';
    }
}

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Detalhes da Garantia</title>

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
                    <h2 class="text-info fw-bold mb-1">
                        <i class="fa-solid fa-file-lines me-2"></i>Detalhes da Garantia/Contrato
                    </h2>

                    <p class="text-muted mb-0">
                        Consulta detalhada da garantia ou contrato de manutenção associado ao equipamento.
                    </p>
                </div>

                <div>
                    <a href="garantias.php" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                    </a>

                    <?php if (!garantia_expirada($garantia)): ?>
                        <a href="editar.php?id_garantia=<?= htmlspecialchars($garantia->id_garantia) ?>" class="btn btn-warning text-white">
                            <i class="fa-solid fa-pen me-2"></i>Editar
                        </a>

                        <a href="apagar.php?id_garantia=<?= htmlspecialchars($garantia->id_garantia) ?>" class="btn btn-outline-danger">
                            <i class="fa-solid fa-ban me-2"></i>Terminar
                        </a>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled>
                            <i class="fa-solid fa-ban me-2"></i>Garantia Expirada
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-primary mb-1">
                            <?= htmlspecialchars($garantia->codigo_inventario ?? '') ?> |
                            <?= htmlspecialchars($garantia->equipamento_designacao ?? '') ?>
                        </h5>

                        <p class="text-muted mb-0">
                            <i class="fa-solid fa-microchip me-2"></i>
                            <?= htmlspecialchars(($garantia->equipamento_marca ?? '') . ' ' . ($garantia->equipamento_modelo ?? '')) ?>
                        </p>
                    </div>

                    <div class="text-end">
                        <span class="badge bg-<?= badge_estado_garantia($garantia->estado ?? '') ?> mb-2">
                            <?= htmlspecialchars($garantia->estado ?? '') ?>
                        </span>

                        <p class="text-muted small mb-0">
                            Validade até 
                            <?= !empty($garantia->data_fim) ? htmlspecialchars(date('d/m/Y', strtotime($garantia->data_fim))) : '—' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">

                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white fw-bold text-primary">
                            <i class="fa-solid fa-shield-halved me-2"></i>Informação da Garantia
                        </div>

                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th>Equipamento:</th>
                                    <td>
                                        <?= htmlspecialchars($garantia->codigo_inventario ?? '') ?> |
                                        <?= htmlspecialchars($garantia->equipamento_designacao ?? '') ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>N.º Série:</th>
                                    <td><?= htmlspecialchars($garantia->equipamento_num_serie ?? '—') ?></td>
                                </tr>

                                <tr>
                                    <th>Tipo:</th>
                                    <td>
                                        <span class="badge bg-<?= badge_tipo_contrato($garantia->tipo_contrato ?? '') ?>">
                                            <?= htmlspecialchars($garantia->tipo_contrato ?? '—') ?>
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        <span class="badge bg-<?= badge_estado_garantia($garantia->estado ?? '') ?>">
                                            <?= htmlspecialchars($garantia->estado ?? '—') ?>
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Criticidade:</th>
                                    <td>
                                        <span class="badge bg-<?= badge_criticidade_garantia($garantia->criticidade ?? '') ?>">
                                            <?= htmlspecialchars($garantia->criticidade ?? '—') ?>
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Data de início:</th>
                                    <td>
                                        <?= !empty($garantia->data_inicio) ? htmlspecialchars(date('d/m/Y', strtotime($garantia->data_inicio))) : '—' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Data de fim:</th>
                                    <td>
                                        <?= !empty($garantia->data_fim) ? htmlspecialchars(date('d/m/Y', strtotime($garantia->data_fim))) : '—' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Duração:</th>
                                    <td><?= htmlspecialchars($duracao) ?></td>
                                </tr>

                                <tr>
                                    <th>Custo anual:</th>
                                    <td>
                                        <?= number_format((float) ($garantia->custo_anual ?? 0), 2, ',', ' ') ?> €
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white fw-bold text-primary">
                            <i class="fa-solid fa-building me-2"></i>Responsável / Fornecedor
                        </div>

                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th>Fornecedor:</th>
                                    <td>
                                        <?= !empty($garantia->fornecedor_nome) ? htmlspecialchars($garantia->fornecedor_nome) : 'Sem fornecedor associado' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>NIF:</th>
                                    <td><?= htmlspecialchars($garantia->fornecedor_nif ?? '—') ?></td>
                                </tr>

                                <tr>
                                    <th>Email:</th>
                                    <td><?= htmlspecialchars($garantia->fornecedor_email ?? '—') ?></td>
                                </tr>

                                <tr>
                                    <th>Telefone:</th>
                                    <td><?= htmlspecialchars($garantia->fornecedor_telefone ?? '—') ?></td>
                                </tr>

                                <tr>
                                    <th>Pessoa de contacto:</th>
                                    <td>
                                        <?= !empty($garantia->pessoa_contacto) 
                                            ? htmlspecialchars($garantia->pessoa_contacto) 
                                            : htmlspecialchars($garantia->fornecedor_pessoa_contacto ?? '—') ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Telefone contacto:</th>
                                    <td><?= htmlspecialchars($garantia->telefone_contacto ?? '—') ?></td>
                                </tr>

                                <tr>
                                    <th>Prioridade:</th>
                                    <td>
                                        <?php if (!empty($garantia->fornecedor_prioridade)): ?>
                                            <span class="badge bg-<?= badge_prioridade_fornecedor($garantia->fornecedor_prioridade) ?>">
                                                <?= htmlspecialchars($garantia->fornecedor_prioridade) ?>
                                            </span>
                                        <?php else: ?>
                                            —
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold text-primary">
                    <i class="fa-solid fa-file-contract me-2"></i>Documentação e Observações
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Documento associado</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                value="<?= htmlspecialchars($garantia->documento_associado ?? '—') ?>" 
                                readonly
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Número do contrato</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                value="<?= htmlspecialchars($garantia->numero_contrato ?? '—') ?>" 
                                readonly
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Caminho/link do documento</label>

                            <?php if (!empty($garantia->caminho_documento)): ?>
                                <div>
                                    <a href="<?= htmlspecialchars($garantia->caminho_documento) ?>" 
                                       target="_blank" 
                                       class="btn btn-outline-primary">
                                        <i class="fa-solid fa-file-arrow-down me-2"></i>Abrir documento
                                    </a>
                                </div>
                            <?php else: ?>
                                <input type="text" class="form-control" value="Sem caminho associado" readonly>
                            <?php endif; ?>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Observações</label>

                            <div class="form-control bg-light" style="min-height: 120px;">
                                <?= !empty($garantia->observacoes) 
                                    ? nl2br(htmlspecialchars($garantia->observacoes)) 
                                    : 'Sem observações registadas.' ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="garantias.php" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Voltar à Lista
                </a>

                <a href="../equipamentos/consultar.php?id_equipamento=<?= htmlspecialchars($garantia->id_equipamento) ?>" class="btn btn-outline-primary">
                    <i class="fa-solid fa-microchip me-2"></i>Ver Equipamento
                </a>
            </div>

        </div>
    </main>
</div>

<script src="../../../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>