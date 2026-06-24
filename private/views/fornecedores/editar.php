
<?php

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/database.php';

redirect_if_not_logged();

$id_fornecedor = $_GET['id_fornecedor'] ?? $_POST['id_fornecedor'] ?? null;

if (!validar_id($id_fornecedor)) {
    redirecionar('fornecedores.php');
}

$fornecedor = buscar_fornecedor_por_id($pdo, $id_fornecedor);

if (!$fornecedor) {
    redirecionar('fornecedores.php');
}

if (fornecedor_inativo($fornecedor)) {
    redirecionar('consultar.php?id_fornecedor=' . $id_fornecedor);
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = recolher_dados_fornecedor_post();

    if (!validar_dados_fornecedor($dados)) {
        $erro = 'Preenche corretamente todos os campos obrigatórios.';
    } else {
        try {
            atualizar_fornecedor($pdo, $id_fornecedor, $dados);
            redirecionar('consultar.php?id_fornecedor=' . $id_fornecedor);
        } catch (PDOException $e) {
            $erro = 'Erro ao atualizar o fornecedor. Verifica se o NIF já existe noutro fornecedor.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Editar Fornecedor</title>

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
                    <h2 class="text-warning fw-bold mb-1">
                        <i class="fa-solid fa-pen-to-square me-2"></i>Editar Fornecedor
                    </h2>

                    <p class="text-muted mb-0">
                        Atualização dos dados comerciais, contactos e relação do fornecedor com o hospital.
                    </p>
                </div>

                <a href="consultar.php?id_fornecedor=<?= htmlspecialchars($fornecedor->id_fornecedor) ?>" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <form id="formEditarFornecedor" action="editar.php?id_fornecedor=<?= htmlspecialchars($fornecedor->id_fornecedor) ?>" method="post" novalidate>

                <input type="hidden" name="id_fornecedor" value="<?= htmlspecialchars($fornecedor->id_fornecedor) ?>">

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        <?= htmlspecialchars($erro) ?>
                    </div>
                <?php endif; ?>

                <!-- Indicador dos passos -->
                <ul class="nav nav-pills nav-fill mb-4">
                    <li class="nav-item">
                        <button class="nav-link active" type="button" data-passo-editar-fornecedor="1">
                            1. Empresa
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" type="button" data-passo-editar-fornecedor="2">
                            2. Contactos
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" type="button" data-passo-editar-fornecedor="3">
                            3. Morada e Relação
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" type="button" data-passo-editar-fornecedor="4">
                            4. Observações
                        </button>
                    </li>
                </ul>

                <!-- PASSO 1 -->
                <section class="passo-editar-fornecedor" id="passoEditarFornecedor1">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white fw-bold text-warning">
                            <i class="fa-solid fa-building me-2"></i>Dados da Empresa
                        </div>

                        <div class="card-body">

                            <div class="alert alert-light border">
                                <strong>Fornecedor atual:</strong>
                                <?= htmlspecialchars($fornecedor->nome_empresa ?? '') ?>

                                <span class="badge bg-<?= badge_estado_fornecedor($fornecedor->estado ?? '') ?> ms-2">
                                    <?= htmlspecialchars($fornecedor->estado ?? '') ?>
                                </span>
                            </div>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nome da Empresa *</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="nome_empresa" 
                                        value="<?= htmlspecialchars($fornecedor->nome_empresa ?? '') ?>"
                                        pattern="[A-Za-z0-9À-ÿ\s\.\-&]+"
                                        title="O nome da empresa só pode conter letras, números, espaços, pontos, hífen e &."
                                        minlength="3"
                                        maxlength="80"
                                        required
                                    >
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">NIF *</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="nif" 
                                        value="<?= htmlspecialchars($fornecedor->nif ?? '') ?>"
                                        pattern="[0-9]{9}"
                                        title="O NIF deve conter exatamente 9 números."
                                        required
                                    >
                                    <div class="form-text">Exatamente 9 números.</div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Estado</label>

                                    <input type="hidden" name="estado" value="<?= htmlspecialchars($fornecedor->estado ?? 'Ativo') ?>">

                                    <div class="form-control bg-light">
                                        <span class="badge bg-<?= badge_estado_fornecedor($fornecedor->estado ?? '') ?>">
                                            <?= htmlspecialchars($fornecedor->estado ?? 'Ativo') ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tipo de Fornecedor *</label>

                                    <select class="form-select" name="tipo_fornecedor" required>
                                        <option value="">Selecione...</option>
                                        <option <?= selecionado($fornecedor->tipo_fornecedor ?? '', 'Fabricante') ?>>Fabricante</option>
                                        <option <?= selecionado($fornecedor->tipo_fornecedor ?? '', 'Distribuidor / Fornecedor Comercial') ?>>Distribuidor / Fornecedor Comercial</option>
                                        <option <?= selecionado($fornecedor->tipo_fornecedor ?? '', 'Empresa de Assistência Técnica') ?>>Empresa de Assistência Técnica</option>
                                        <option <?= selecionado($fornecedor->tipo_fornecedor ?? '', 'Fornecedor de Consumíveis/Acessórios') ?>>Fornecedor de Consumíveis/Acessórios</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Área de Atuação *</label>

                                    <select class="form-select" name="area_atuacao" required>
                                        <option value="">Selecione...</option>
                                        <option <?= selecionado($fornecedor->area_atuacao ?? '', 'Diagnóstico e Imagiologia') ?>>Diagnóstico e Imagiologia</option>
                                        <option <?= selecionado($fornecedor->area_atuacao ?? '', 'Monitorização') ?>>Monitorização</option>
                                        <option <?= selecionado($fornecedor->area_atuacao ?? '', 'Suporte de Vida') ?>>Suporte de Vida</option>
                                        <option <?= selecionado($fornecedor->area_atuacao ?? '', 'Laboratório') ?>>Laboratório</option>
                                        <option <?= selecionado($fornecedor->area_atuacao ?? '', 'Consumíveis Hospitalares') ?>>Consumíveis Hospitalares</option>
                                        <option <?= selecionado($fornecedor->area_atuacao ?? '', 'Assistência Técnica') ?>>Assistência Técnica</option>
                                        <option <?= selecionado($fornecedor->area_atuacao ?? '', 'Outro') ?>>Outro</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>

                <!-- PASSO 2 -->
                <section class="passo-editar-fornecedor d-none" id="passoEditarFornecedor2">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white fw-bold text-warning">
                            <i class="fa-solid fa-address-book me-2"></i>Contactos
                        </div>

                        <div class="card-body">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email de Contacto *</label>
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        name="email" 
                                        value="<?= htmlspecialchars($fornecedor->email ?? '') ?>"
                                        required
                                    >
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Telefone da Empresa</label>
                                    <input 
                                        type="tel" 
                                        class="form-control" 
                                        name="telefone" 
                                        value="<?= htmlspecialchars($fornecedor->telefone ?? '') ?>"
                                        pattern="[0-9]{9}"
                                        title="O telefone deve conter exatamente 9 dígitos."
                                    >
                                    <div class="form-text">9 dígitos.</div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Website</label>
                                    <input 
                                        type="url" 
                                        class="form-control" 
                                        name="website" 
                                        value="<?= htmlspecialchars($fornecedor->website ?? '') ?>"
                                        placeholder="https://www.exemplo.pt"
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Pessoa de Contacto</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="pessoa_contacto" 
                                        value="<?= htmlspecialchars($fornecedor->pessoa_contacto ?? '') ?>"
                                        pattern="[A-Za-zÀ-ÿ\s]+"
                                        title="A pessoa de contacto deve conter apenas letras e espaços."
                                        maxlength="80"
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Telefone da Pessoa de Contacto</label>
                                    <input 
                                        type="tel" 
                                        class="form-control" 
                                        name="tel_pessoa" 
                                        value="<?= htmlspecialchars($fornecedor->tel_pessoa ?? '') ?>"
                                        pattern="[0-9]{9}"
                                        title="O telefone deve conter exatamente 9 dígitos."
                                    >
                                    <div class="form-text">9 dígitos.</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>

                <!-- PASSO 3 -->
                <section class="passo-editar-fornecedor d-none" id="passoEditarFornecedor3">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white fw-bold text-warning">
                            <i class="fa-solid fa-map-location-dot me-2"></i>Morada e Relação com o Hospital
                        </div>

                        <div class="card-body">
                            <div class="row g-3">

                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Morada</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="morada" 
                                        value="<?= htmlspecialchars($fornecedor->morada ?? '') ?>"
                                        pattern="[A-Za-z0-9À-ÿ\s\.\,\-ºª]+"
                                        title="A morada só pode conter letras, números, espaços, vírgulas, pontos, hífen, º e ª."
                                        maxlength="120"
                                    >
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Contrato Ativo</label>

                                    <select class="form-select" name="contrato_ativo">
                                        <option <?= selecionado($fornecedor->contrato_ativo ?? '', 'Sim') ?>>Sim</option>
                                        <option <?= selecionado($fornecedor->contrato_ativo ?? '', 'Não') ?>>Não</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tipo de Relação com o Hospital</label>

                                    <select class="form-select" name="relacao_hospital">
                                        <option value="">Sem relação definida</option>
                                        <option <?= selecionado($fornecedor->relacao_hospital ?? '', 'Fornecedor principal') ?>>Fornecedor principal</option>
                                        <option <?= selecionado($fornecedor->relacao_hospital ?? '', 'Fornecedor secundário') ?>>Fornecedor secundário</option>
                                        <option <?= selecionado($fornecedor->relacao_hospital ?? '', 'Prestador de assistência técnica') ?>>Prestador de assistência técnica</option>
                                        <option <?= selecionado($fornecedor->relacao_hospital ?? '', 'Fornecedor de consumíveis') ?>>Fornecedor de consumíveis</option>
                                        <option <?= selecionado($fornecedor->relacao_hospital ?? '', 'Fabricante sem contrato direto') ?>>Fabricante sem contrato direto</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Prioridade de Contacto</label>

                                    <select class="form-select" name="prioridade_contacto">
                                        <option <?= selecionado($fornecedor->prioridade_contacto ?? '', 'Normal') ?>>Normal</option>
                                        <option <?= selecionado($fornecedor->prioridade_contacto ?? '', 'Alta') ?>>Alta</option>
                                        <option <?= selecionado($fornecedor->prioridade_contacto ?? '', 'Urgente') ?>>Urgente</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Equipamentos Associados</label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        value="<?= htmlspecialchars($fornecedor->total_equipamentos_associados ?? 0) ?>"
                                        min="0"
                                        readonly
                                    >
                                    <div class="form-text">Campo informativo.</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>

                <!-- PASSO 4 -->
                <section class="passo-editar-fornecedor d-none" id="passoEditarFornecedor4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white fw-bold text-warning">
                            <i class="fa-solid fa-clipboard me-2"></i>Observações e Confirmação
                        </div>

                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Observações / Notas</label>

                                <textarea 
                                    class="form-control" 
                                    name="observacoes" 
                                    rows="5"
                                    maxlength="500"
                                ><?= htmlspecialchars($fornecedor->observacoes ?? '') ?></textarea>

                                <div class="form-text">Máximo de 500 caracteres.</div>
                            </div>

                            <div class="alert alert-warning">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                Confirme os dados antes de guardar as alterações ao fornecedor.
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Botões -->
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" id="btnAnteriorEditarFornecedor" disabled>
                        <i class="fa-solid fa-arrow-left me-2"></i>Anterior
                    </button>

                    <div>
                        <a href="consultar.php?id_fornecedor=<?= htmlspecialchars($fornecedor->id_fornecedor) ?>" class="btn btn-secondary me-2">
                            Cancelar
                        </a>

                        <button type="button" class="btn btn-warning px-4 text-white" id="btnSeguinteEditarFornecedor">
                            Seguinte
                            <i class="fa-solid fa-arrow-right ms-2"></i>
                        </button>

                        <button type="reset" class="btn btn-outline-warning px-4" id="btnReporEditarFornecedor">
                            <i class="fa-solid fa-rotate-left me-2"></i>Repor Dados
                        </button>

                        <button type="submit" class="btn btn-success px-4" id="btnGuardarEditarFornecedor">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Alterações
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </main>
</div>

<script src="../../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../../assets/js/1240881.js"></script>

</body>
</html>