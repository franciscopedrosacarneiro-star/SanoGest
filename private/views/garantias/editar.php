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

$equipamentos = listar_equipamentos_para_garantia($pdo);
$fornecedores = listar_fornecedores_para_garantia($pdo);

$valores = $garantia;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = recolher_dados_garantia_post();

    if (!validar_dados_garantia($dados)) {
        $erro = 'Preenche corretamente todos os campos obrigatórios. Confirma também as datas e o telefone.';
        $valores = (object) array_merge((array) $garantia, $dados);
    } else {
        try {
            atualizar_garantia($pdo, $id_garantia, $dados);
            redirecionar('consultar.php?id_garantia=' . $id_garantia);
        } catch (PDOException $e) {
            $erro = 'Erro ao atualizar a garantia ou contrato.';
            $valores = (object) array_merge((array) $garantia, $dados);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Editar Garantia</title>

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
                    <h2 class="text-warning fw-bold mb-1">
                        <i class="fa-solid fa-pen-to-square me-2"></i>Editar Garantia/Contrato
                    </h2>

                    <p class="text-muted mb-0">
                        Atualização dos dados de uma garantia ou contrato de manutenção associado a equipamento médico.
                    </p>
                </div>

                <a href="consultar.php?id_garantia=<?= htmlspecialchars($id_garantia) ?>" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <?php if (!empty($erro)): ?>
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    <?= htmlspecialchars($erro) ?>
                </div>
            <?php endif; ?>

            <form action="editar.php?id_garantia=<?= htmlspecialchars($id_garantia) ?>" method="POST" novalidate>
                <input type="hidden" name="id_garantia" value="<?= htmlspecialchars($id_garantia) ?>">

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold text-primary mb-1">
                                <?= htmlspecialchars($garantia->codigo_inventario ?? '') ?> |
                                <?= htmlspecialchars($garantia->equipamento_designacao ?? '') ?>
                            </h5>

                            <p class="text-muted mb-0">
                                <i class="fa-solid fa-file-contract me-2"></i>
                                <?= htmlspecialchars($garantia->numero_contrato ?? 'Sem número de contrato') ?>
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

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-microchip me-2"></i>Equipamento e Fornecedor
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="id_equipamento" class="form-label fw-bold">Equipamento associado *</label>
                                <select id="id_equipamento" name="id_equipamento" class="form-select" required>
                                    <option value="">Selecionar equipamento...</option>

                                    <?php foreach ($equipamentos as $equipamento): ?>
                                        <option 
                                            value="<?= htmlspecialchars($equipamento->id_equipamento) ?>"
                                            <?= selecionado((string) ($valores->id_equipamento ?? ''), (string) $equipamento->id_equipamento) ?>
                                        >
                                            <?= htmlspecialchars($equipamento->codigo_inventario ?? '') ?> |
                                            <?= htmlspecialchars($equipamento->designacao ?? '') ?>
                                            <?= !empty($equipamento->marca) ? ' — ' . htmlspecialchars($equipamento->marca) : '' ?>
                                            <?= !empty($equipamento->modelo) ? ' ' . htmlspecialchars($equipamento->modelo) : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="id_fornecedor" class="form-label fw-bold">Fornecedor / Entidade responsável</label>
                                <select id="id_fornecedor" name="id_fornecedor" class="form-select">
                                    <option value="">Sem fornecedor associado</option>

                                    <?php foreach ($fornecedores as $fornecedor): ?>
                                        <option 
                                            value="<?= htmlspecialchars($fornecedor->id_fornecedor) ?>"
                                            <?= selecionado((string) ($valores->id_fornecedor ?? ''), (string) $fornecedor->id_fornecedor) ?>
                                        >
                                            <?= htmlspecialchars($fornecedor->nome_empresa ?? '') ?>
                                            <?= !empty($fornecedor->tipo_fornecedor) ? ' — ' . htmlspecialchars($fornecedor->tipo_fornecedor) : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <?php if (!empty($garantia->fornecedor_nome) && garantia_expirada($garantia) === false): ?>
                                    <div class="form-text">
                                        Fornecedor atual: <?= htmlspecialchars($garantia->fornecedor_nome) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label for="numero_contrato" class="form-label fw-bold">Número do contrato</label>
                                <input 
                                    type="text" 
                                    id="numero_contrato" 
                                    name="numero_contrato" 
                                    class="form-control" 
                                    value="<?= htmlspecialchars($valores->numero_contrato ?? '') ?>"
                                    placeholder="Ex: CON-2026-001" 
                                    maxlength="40"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="criticidade" class="form-label fw-bold">Criticidade *</label>
                                <select id="criticidade" name="criticidade" class="form-select" required>
                                    <option value="">Selecionar criticidade...</option>
                                    <option <?= selecionado($valores->criticidade ?? '', 'Baixa') ?>>Baixa</option>
                                    <option <?= selecionado($valores->criticidade ?? '', 'Média') ?>>Média</option>
                                    <option <?= selecionado($valores->criticidade ?? '', 'Alta') ?>>Alta</option>
                                    <option <?= selecionado($valores->criticidade ?? '', 'Suporte de Vida') ?>>Suporte de Vida</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-file-contract me-2"></i>Dados da Garantia/Contrato
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label for="tipo_contrato" class="form-label fw-bold">Tipo *</label>
                                <select id="tipo_contrato" name="tipo_contrato" class="form-select" required>
                                    <option value="">Selecionar tipo...</option>
                                    <option <?= selecionado($valores->tipo_contrato ?? '', 'Garantia') ?>>Garantia</option>
                                    <option <?= selecionado($valores->tipo_contrato ?? '', 'Preventiva') ?>>Preventiva</option>
                                    <option <?= selecionado($valores->tipo_contrato ?? '', 'Corretiva') ?>>Corretiva</option>
                                    <option <?= selecionado($valores->tipo_contrato ?? '', 'Full Service') ?>>Full Service</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="estado" class="form-label fw-bold">Estado *</label>
                                <select id="estado" name="estado" class="form-select" required>
                                    <option <?= selecionado($valores->estado ?? '', 'Ativa') ?>>Ativa</option>
                                    <option <?= selecionado($valores->estado ?? '', 'A Terminar') ?>>A Terminar</option>
                                    <option <?= selecionado($valores->estado ?? '', 'Expirada') ?>>Expirada</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="custo_anual" class="form-label fw-bold">Custo anual (€)</label>
                                <input 
                                    type="number" 
                                    id="custo_anual" 
                                    name="custo_anual" 
                                    class="form-control" 
                                    min="0" 
                                    step="0.01" 
                                    value="<?= htmlspecialchars($valores->custo_anual ?? 0) ?>"
                                    placeholder="Ex: 1250.00"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="data_inicio" class="form-label fw-bold">Data de início *</label>
                                <input 
                                    type="date" 
                                    id="data_inicio" 
                                    name="data_inicio" 
                                    class="form-control" 
                                    value="<?= htmlspecialchars($valores->data_inicio ?? '') ?>"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="data_fim" class="form-label fw-bold">Data de fim *</label>
                                <input 
                                    type="date" 
                                    id="data_fim" 
                                    name="data_fim" 
                                    class="form-control" 
                                    value="<?= htmlspecialchars($valores->data_fim ?? '') ?>"
                                    required
                                >
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-user-tie me-2"></i>Contacto e Documentação
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="pessoa_contacto" class="form-label fw-bold">Pessoa de contacto</label>
                                <input 
                                    type="text" 
                                    id="pessoa_contacto" 
                                    name="pessoa_contacto" 
                                    class="form-control" 
                                    value="<?= htmlspecialchars($valores->pessoa_contacto ?? '') ?>"
                                    placeholder="Ex: Ana Martins" 
                                    maxlength="80"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="telefone_contacto" class="form-label fw-bold">Telefone de contacto</label>
                                <input 
                                    type="tel" 
                                    id="telefone_contacto" 
                                    name="telefone_contacto" 
                                    class="form-control" 
                                    value="<?= htmlspecialchars($valores->telefone_contacto ?? '') ?>"
                                    placeholder="Ex: 210000000" 
                                    maxlength="9"
                                    pattern="[0-9]{9}"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="documento_associado" class="form-label fw-bold">Nome do documento</label>
                                <input 
                                    type="text" 
                                    id="documento_associado" 
                                    name="documento_associado" 
                                    class="form-control" 
                                    value="<?= htmlspecialchars($valores->documento_associado ?? '') ?>"
                                    placeholder="Ex: contrato_eqp001.pdf" 
                                    maxlength="120"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="caminho_documento" class="form-label fw-bold">Caminho/link do documento</label>
                                <input 
                                    type="text" 
                                    id="caminho_documento" 
                                    name="caminho_documento" 
                                    class="form-control" 
                                    value="<?= htmlspecialchars($valores->caminho_documento ?? '') ?>"
                                    placeholder="Ex: ../../../assets/docs/contrato_eqp001.pdf" 
                                    maxlength="180"
                                >
                                <div class="form-text">
                                    Por agora vamos guardar apenas o nome/caminho do documento, sem upload real.
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="observacoes" class="form-label fw-bold">Observações</label>
                                <textarea 
                                    id="observacoes" 
                                    name="observacoes" 
                                    class="form-control" 
                                    rows="4" 
                                    maxlength="500"
                                    placeholder="Observações sobre a garantia, contrato, SLA, condições ou cobertura..."
                                ><?= htmlspecialchars($valores->observacoes ?? '') ?></textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="consultar.php?id_garantia=<?= htmlspecialchars($id_garantia) ?>" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-circle-info me-2"></i>Ver Detalhes
                    </a>

                    <div>
                        <a href="consultar.php?id_garantia=<?= htmlspecialchars($id_garantia) ?>" class="btn btn-secondary me-2">
                            Cancelar
                        </a>

                        <button type="reset" class="btn btn-outline-warning me-2">
                            <i class="fa-solid fa-rotate-left me-2"></i>Repor Dados
                        </button>

                        <button type="submit" class="btn btn-warning px-4 text-white">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Alterações
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </main>
</div>

<script src="../../../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>