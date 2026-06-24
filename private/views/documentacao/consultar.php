<?php

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/database.php';

redirect_if_not_logged();

$id_documento = $_GET['id_documento'] ?? null;

if (!validar_id($id_documento)) {
    redirecionar('documentacao.php');
}

$documento = buscar_documento_por_id($pdo, $id_documento);

if (!$documento) {
    redirecionar('documentacao.php');
}

$tamanhoFormatado = '—';

if (!empty($documento->tamanho_bytes) && is_numeric($documento->tamanho_bytes)) {
    $tamanho = (int) $documento->tamanho_bytes;

    if ($tamanho >= 1048576) {
        $tamanhoFormatado = number_format($tamanho / 1048576, 2, ',', ' ') . ' MB';
    } elseif ($tamanho >= 1024) {
        $tamanhoFormatado = number_format($tamanho / 1024, 2, ',', ' ') . ' KB';
    } else {
        $tamanhoFormatado = $tamanho . ' bytes';
    }
}

$linkAssociacao = null;
$textoBotaoAssociacao = 'Ver Entidade Associada';
$iconeAssociacao = 'fa-link';

if (($documento->tipo_associacao ?? '') === 'Equipamento' && !empty($documento->id_equipamento)) {
    $linkAssociacao = '../equipamentos/consultar.php?id_equipamento=' . $documento->id_equipamento;
    $textoBotaoAssociacao = 'Ver Equipamento';
    $iconeAssociacao = 'fa-microchip';
}

if (($documento->tipo_associacao ?? '') === 'Fornecedor' && !empty($documento->id_fornecedor)) {
    $linkAssociacao = '../fornecedores/consultar.php?id_fornecedor=' . $documento->id_fornecedor;
    $textoBotaoAssociacao = 'Ver Fornecedor';
    $iconeAssociacao = 'fa-truck-medical';
}

if (
    (($documento->tipo_associacao ?? '') === 'Garantia' || ($documento->tipo_associacao ?? '') === 'Contrato')
    && !empty($documento->id_garantia)
) {
    $linkAssociacao = '../garantias/consultar.php?id_garantia=' . $documento->id_garantia;
    $textoBotaoAssociacao = 'Ver Garantia/Contrato';
    $iconeAssociacao = 'fa-shield-halved';
}

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Detalhes do Documento</title>

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
                        <i class="fa-solid fa-file-lines me-2"></i>Detalhes do Documento
                    </h2>

                    <p class="text-muted mb-0">
                        Consulta da informação documental associada ao inventário hospitalar.
                    </p>
                </div>

                <div>
                    <a href="documentacao.php" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                    </a>

                    <?php if (!documento_expirado($documento)): ?>
                        <a href="editar.php?id_documento=<?= htmlspecialchars($documento->id_documento) ?>" class="btn btn-warning text-white">
                            <i class="fa-solid fa-pen me-2"></i>Editar
                        </a>

                        <a href="apagar.php?id_documento=<?= htmlspecialchars($documento->id_documento) ?>" class="btn btn-outline-danger">
                            <i class="fa-solid fa-ban me-2"></i>Expirar
                        </a>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled>
                            <i class="fa-solid fa-ban me-2"></i>Documento Expirado
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-primary mb-1">
                            <?= htmlspecialchars($documento->nome_documento ?? '') ?>
                        </h5>

                        <p class="text-muted mb-0">
                            <i class="fa-solid fa-file-pdf me-2"></i>
                            <?= htmlspecialchars($documento->ficheiro ?? 'Sem ficheiro associado') ?>
                        </p>
                    </div>

                    <div class="text-end">
                        <span class="badge bg-<?= badge_estado_documento($documento->estado ?? '') ?> mb-2">
                            <?= htmlspecialchars($documento->estado ?? '') ?>
                        </span>

                        <p class="text-muted small mb-0">
                            Validade:
                            <?= !empty($documento->data_validade) 
                                ? htmlspecialchars(date('d/m/Y', strtotime($documento->data_validade))) 
                                : 'Sem validade' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">

                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white fw-bold text-primary">
                            <i class="fa-solid fa-file-contract me-2"></i>Informação do Documento
                        </div>

                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th>Nome:</th>
                                    <td><?= htmlspecialchars($documento->nome_documento ?? '—') ?></td>
                                </tr>

                                <tr>
                                    <th>Tipo:</th>
                                    <td>
                                        <span class="badge bg-<?= badge_tipo_documento($documento->tipo_documento ?? '') ?>">
                                            <?= htmlspecialchars($documento->tipo_documento ?? '—') ?>
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        <span class="badge bg-<?= badge_estado_documento($documento->estado ?? '') ?>">
                                            <?= htmlspecialchars($documento->estado ?? '—') ?>
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Referência:</th>
                                    <td><?= htmlspecialchars($documento->referencia ?? '—') ?></td>
                                </tr>

                                <tr>
                                    <th>Data de emissão:</th>
                                    <td>
                                        <?= !empty($documento->data_emissao) 
                                            ? htmlspecialchars(date('d/m/Y', strtotime($documento->data_emissao))) 
                                            : '—' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Data de validade:</th>
                                    <td>
                                        <?= !empty($documento->data_validade) 
                                            ? htmlspecialchars(date('d/m/Y', strtotime($documento->data_validade))) 
                                            : 'Sem validade' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Ficheiro:</th>
                                    <td><?= htmlspecialchars($documento->ficheiro ?? '—') ?></td>
                                </tr>

                                <tr>
                                    <th>Tamanho:</th>
                                    <td><?= htmlspecialchars($tamanhoFormatado) ?></td>
                                </tr>

                                <tr>
                                    <th>Tipo MIME:</th>
                                    <td><?= htmlspecialchars($documento->mime_type ?? '—') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white fw-bold text-primary">
                            <i class="fa-solid fa-link me-2"></i>Associação
                        </div>

                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th>Tipo de associação:</th>
                                    <td>
                                        <span class="badge bg-<?= badge_associacao_documento($documento->tipo_associacao ?? '') ?>">
                                            <?= htmlspecialchars($documento->tipo_associacao ?? '—') ?>
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Entidade associada:</th>
                                    <td>
                                        <?= !empty($documento->entidade_associada) 
                                            ? htmlspecialchars($documento->entidade_associada) 
                                            : '—' ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>ID Equipamento:</th>
                                    <td><?= htmlspecialchars($documento->id_equipamento ?? '—') ?></td>
                                </tr>

                                <tr>
                                    <th>ID Fornecedor:</th>
                                    <td><?= htmlspecialchars($documento->id_fornecedor ?? '—') ?></td>
                                </tr>

                                <tr>
                                    <th>ID Garantia/Contrato:</th>
                                    <td><?= htmlspecialchars($documento->id_garantia ?? '—') ?></td>
                                </tr>
                            </table>

                            <?php if (!empty($linkAssociacao)): ?>
                                <a href="<?= htmlspecialchars($linkAssociacao) ?>" class="btn btn-outline-primary mt-3">
                                    <i class="fa-solid <?= htmlspecialchars($iconeAssociacao) ?> me-2"></i>
                                    <?= htmlspecialchars($textoBotaoAssociacao) ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold text-primary">
                    <i class="fa-solid fa-download me-2"></i>Ficheiro e Observações
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nome do ficheiro</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                value="<?= htmlspecialchars($documento->ficheiro ?? '—') ?>" 
                                readonly
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Caminho/link do ficheiro</label>

                            <?php if (!empty($documento->caminho_ficheiro)): ?>
                                <div>
                                    <a href="<?= htmlspecialchars($documento->caminho_ficheiro) ?>" 
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
                                <?= !empty($documento->observacoes) 
                                    ? nl2br(htmlspecialchars($documento->observacoes)) 
                                    : 'Sem observações registadas.' ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="documentacao.php" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Voltar à Lista
                </a>

                <?php if (!empty($linkAssociacao)): ?>
                    <a href="<?= htmlspecialchars($linkAssociacao) ?>" class="btn btn-outline-primary">
                        <i class="fa-solid <?= htmlspecialchars($iconeAssociacao) ?> me-2"></i>
                        <?= htmlspecialchars($textoBotaoAssociacao) ?>
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </main>
</div>

<script src="../../../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>