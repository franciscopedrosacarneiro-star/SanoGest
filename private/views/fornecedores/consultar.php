<?php

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/database.php';

redirect_if_not_logged();

$id_fornecedor = $_GET['id_fornecedor'] ?? null;

if (!validar_id($id_fornecedor)) {
    redirecionar('fornecedores.php');
}

$fornecedor = buscar_fornecedor_por_id($pdo, $id_fornecedor);

if (!$fornecedor) {
    redirecionar('fornecedores.php');
}

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Consultar Fornecedor</title>
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
                    <a href="fornecedores.php" class="nav-link text-dark">
                        <i class="fa-solid fa-truck-medical me-2"></i>Fornecedores
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
                        <i class="fa-solid fa-circle-info me-2"></i>
                        Detalhes do Fornecedor
                    </h2>

                    <p class="text-muted mb-0">
                        Consulta dos dados comerciais, contactos e relação com o hospital.
                    </p>
                </div>

                <div>
                    <a href="fornecedores.php" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                    </a>

                    <?php if (!fornecedor_inativo($fornecedor)): ?>
                        <a href="editar.php?id_fornecedor=<?= htmlspecialchars($fornecedor->id_fornecedor) ?>" class="btn btn-warning text-white">
                            <i class="fa-solid fa-pen me-2"></i>Editar
                        </a>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled>
                            <i class="fa-solid fa-ban me-2"></i>Fornecedor Inativo
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="alert alert-light border mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">
                            <?= htmlspecialchars($fornecedor->nome_empresa ?? '') ?>
                        </h4>

                        <span class="text-muted">
                            NIF: <?= htmlspecialchars($fornecedor->nif ?? '—') ?>
                        </span>
                    </div>

                    <div>
                        <span class="badge bg-<?= badge_estado_fornecedor($fornecedor->estado ?? '') ?> fs-6">
                            <?= htmlspecialchars($fornecedor->estado ?? '') ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-light fw-bold">
                            <i class="fa-solid fa-building me-2 text-primary"></i>
                            Dados da Empresa
                        </div>

                        <div class="card-body">
                            <p>
                                <strong>Nome da Empresa:</strong>
                                <?= htmlspecialchars($fornecedor->nome_empresa ?? '—') ?>
                            </p>

                            <p>
                                <strong>NIF:</strong>
                                <?= htmlspecialchars($fornecedor->nif ?? '—') ?>
                            </p>

                            <p>
                                <strong>Tipo de Fornecedor:</strong>
                                <span class="badge bg-<?= badge_tipo_fornecedor($fornecedor->tipo_fornecedor ?? '') ?>">
                                    <?= htmlspecialchars($fornecedor->tipo_fornecedor ?? '—') ?>
                                </span>
                            </p>

                            <p>
                                <strong>Área de Atuação:</strong>
                                <?= htmlspecialchars($fornecedor->area_atuacao ?? '—') ?>
                            </p>

                            <p>
                                <strong>Estado:</strong>
                                <span class="badge bg-<?= badge_estado_fornecedor($fornecedor->estado ?? '') ?>">
                                    <?= htmlspecialchars($fornecedor->estado ?? '—') ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-light fw-bold">
                            <i class="fa-solid fa-address-book me-2 text-primary"></i>
                            Contactos
                        </div>

                        <div class="card-body">
                            <p>
                                <strong>Email:</strong>
                                <?= htmlspecialchars($fornecedor->email ?? '—') ?>
                            </p>

                            <p>
                                <strong>Telefone da Empresa:</strong>
                                <?= htmlspecialchars($fornecedor->telefone ?? '—') ?>
                            </p>

                            <p>
                                <strong>Website:</strong>
                                <?php if (!empty($fornecedor->website)): ?>
                                    <a href="<?= htmlspecialchars($fornecedor->website) ?>" target="_blank">
                                        <?= htmlspecialchars($fornecedor->website) ?>
                                    </a>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </p>

                            <p>
                                <strong>Pessoa de Contacto:</strong>
                                <?= htmlspecialchars($fornecedor->pessoa_contacto ?? '—') ?>
                            </p>

                            <p>
                                <strong>Telefone da Pessoa de Contacto:</strong>
                                <?= htmlspecialchars($fornecedor->tel_pessoa ?? '—') ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light fw-bold">
                            <i class="fa-solid fa-handshake me-2 text-primary"></i>
                            Relação com o Hospital
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <strong>Contrato Ativo:</strong>
                                    <?= htmlspecialchars($fornecedor->contrato_ativo ?? '—') ?>
                                </div>

                                <div class="col-md-4">
                                    <strong>Relação:</strong>
                                    <?= htmlspecialchars($fornecedor->relacao_hospital ?? '—') ?>
                                </div>

                                <div class="col-md-4">
                                    <strong>Prioridade:</strong>
                                    <span class="badge bg-<?= badge_prioridade_fornecedor($fornecedor->prioridade_contacto ?? '') ?>">
                                        <?= htmlspecialchars($fornecedor->prioridade_contacto ?? '—') ?>
                                    </span>
                                </div>

                                <div class="col-md-4">
                                    <strong>Equipamentos Associados:</strong>
                                    <?= htmlspecialchars($fornecedor->total_equipamentos_associados ?? 0) ?>
                                </div>

                                <div class="col-md-8">
                                    <strong>Morada:</strong>
                                    <?= htmlspecialchars($fornecedor->morada ?? '—') ?>
                                </div>
                            </div>
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
                    <?= !empty($fornecedor->observacoes) ? nl2br(htmlspecialchars($fornecedor->observacoes)) : 'Sem observações registadas.' ?>
                </div>
            </div>

        </div>
    </main>
</div>

<script src="../../../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>