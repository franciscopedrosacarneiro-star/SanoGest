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
$valores = $localizacao;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = recolher_dados_localizacao_post();

    if (!validar_dados_localizacao($dados)) {
        $erro = 'Preenche corretamente todos os campos obrigatórios.';
        $valores = (object) array_merge((array) $localizacao, $dados);
    } else {
        try {
            atualizar_localizacao($pdo, $id_localizacao, $dados);
            redirecionar('consultar.php?id_localizacao=' . $id_localizacao);
        } catch (PDOException $e) {
            $erro = 'Erro ao atualizar a localização.';
            $valores = (object) array_merge((array) $localizacao, $dados);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Editar Localização</title>

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
                        <i class="fa-solid fa-pen-to-square me-2"></i>Editar Localização
                    </h2>

                    <p class="text-muted mb-0">
                        Atualize os dados da localização física associada aos equipamentos médicos.
                    </p>
                </div>

                <a href="consultar.php?id_localizacao=<?= htmlspecialchars($id_localizacao) ?>" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <?php if (!empty($erro)): ?>
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    <?= htmlspecialchars($erro) ?>
                </div>
            <?php endif; ?>

            <form action="editar.php?id_localizacao=<?= htmlspecialchars($id_localizacao) ?>" method="POST">
                <input type="hidden" name="id_localizacao" value="<?= htmlspecialchars($id_localizacao) ?>">

                <!-- Resumo da localização -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold text-primary mb-1">
                                <?= htmlspecialchars($localizacao->edificio ?? '') ?> |
                                Piso <?= htmlspecialchars($localizacao->piso ?? '') ?> |
                                <?= htmlspecialchars($localizacao->servico ?? '') ?>
                            </h5>

                            <p class="text-muted mb-0">
                                <i class="fa-solid fa-door-open me-2"></i>
                                <?= htmlspecialchars($localizacao->sala ?? '') ?>
                            </p>
                        </div>

                        <div class="text-end">
                            <span class="badge bg-<?= badge_estado_localizacao($localizacao->estado ?? '') ?> mb-2">
                                <?= htmlspecialchars($localizacao->estado ?? '') ?>
                            </span>

                            <p class="text-muted small mb-0">
                                <?= htmlspecialchars($localizacao->total_equipamentos_associados ?? 0) ?> equipamentos associados
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Informações principais -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-map-location-dot me-2"></i>1. Informações da Localização
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Edifício *</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="edificio" 
                                    value="<?= htmlspecialchars($valores->edificio ?? '') ?>"
                                    pattern="[A-Za-zÀ-ÿ0-9\s\-]+"
                                    title="O edifício só pode conter letras, números, espaços e hífen."
                                    minlength="1"
                                    maxlength="40"
                                    required
                                >
                                <div class="form-text">Letras, números, espaços e hífen.</div>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold">Piso *</label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    name="piso" 
                                    value="<?= htmlspecialchars($valores->piso ?? '') ?>"
                                    min="0"
                                    max="20"
                                    required
                                >
                                <div class="form-text">Entre 0 e 20.</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Serviço/Departamento *</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="servico" 
                                    value="<?= htmlspecialchars($valores->servico ?? '') ?>"
                                    pattern="[A-Za-zÀ-ÿ0-9\s\-\/]+"
                                    title="O serviço só pode conter letras, números, espaços, hífen e barra."
                                    minlength="3"
                                    maxlength="80"
                                    required
                                >
                                <div class="form-text">Ex: Bloco Operatório, UCI, Radiologia.</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Sala/Gabinete *</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="sala" 
                                    value="<?= htmlspecialchars($valores->sala ?? '') ?>"
                                    pattern="[A-Za-zÀ-ÿ0-9\s\-]+"
                                    title="A sala/gabinete só pode conter letras, números, espaços e hífen."
                                    minlength="2"
                                    maxlength="40"
                                    required
                                >
                                <div class="form-text">Ex: Sala 04, Box 03.</div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Caracterização -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-hospital me-2"></i>2. Caracterização do Espaço
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tipo de Área *</label>
                                <select class="form-select" name="tipo_area" required>
                                    <option value="">Selecione...</option>
                                    <option <?= selecionado($valores->tipo_area ?? '', 'Área Crítica') ?>>Área Crítica</option>
                                    <option <?= selecionado($valores->tipo_area ?? '', 'Diagnóstico') ?>>Diagnóstico</option>
                                    <option <?= selecionado($valores->tipo_area ?? '', 'Internamento') ?>>Internamento</option>
                                    <option <?= selecionado($valores->tipo_area ?? '', 'Laboratório') ?>>Laboratório</option>
                                    <option <?= selecionado($valores->tipo_area ?? '', 'Armazém') ?>>Armazém</option>
                                    <option <?= selecionado($valores->tipo_area ?? '', 'Administrativa') ?>>Administrativa</option>
                                    <option <?= selecionado($valores->tipo_area ?? '', 'Outra') ?>>Outra</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Estado da Localização *</label>
                                <select class="form-select" name="estado" required>
                                    <option <?= selecionado($valores->estado ?? '', 'Ativa') ?>>Ativa</option>
                                    <option <?= selecionado($valores->estado ?? '', 'Inativa') ?>>Inativa</option>
                                    <option <?= selecionado($valores->estado ?? '', 'Em obras') ?>>Em obras</option>
                                    <option <?= selecionado($valores->estado ?? '', 'Indisponível') ?>>Indisponível</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Capacidade estimada de equipamentos</label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    name="capacidade_equipamentos" 
                                    value="<?= htmlspecialchars($valores->capacidade_equipamentos ?? 0) ?>"
                                    min="0"
                                    max="200"
                                >
                                <div class="form-text">Valor entre 0 e 200.</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Equipamentos associados</label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    value="<?= htmlspecialchars($localizacao->total_equipamentos_associados ?? 0) ?>"
                                    min="0"
                                    readonly
                                >
                                <div class="form-text">Campo informativo.</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Acesso restrito</label>
                                <select class="form-select" name="acesso_restrito">
                                    <option <?= selecionado($valores->acesso_restrito ?? '', 'Sim') ?>>Sim</option>
                                    <option <?= selecionado($valores->acesso_restrito ?? '', 'Não') ?>>Não</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Prioridade técnica</label>
                                <select class="form-select" name="prioridade_tecnica">
                                    <option <?= selecionado($valores->prioridade_tecnica ?? '', 'Normal') ?>>Normal</option>
                                    <option <?= selecionado($valores->prioridade_tecnica ?? '', 'Alta') ?>>Alta</option>
                                    <option <?= selecionado($valores->prioridade_tecnica ?? '', 'Crítica') ?>>Crítica</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-clipboard me-2"></i>3. Observações
                    </div>

                    <div class="card-body">
                        <label class="form-label fw-bold">Descrição / Observações</label>
                        <textarea 
                            class="form-control" 
                            name="observacoes" 
                            rows="4"
                            maxlength="500"
                            placeholder="Informações adicionais sobre o espaço, acessibilidade, equipamentos permitidos ou estado da sala..."
                        ><?= htmlspecialchars($valores->observacoes ?? '') ?></textarea>
                        <div class="form-text">Máximo de 500 caracteres.</div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="consultar.php?id_localizacao=<?= htmlspecialchars($id_localizacao) ?>" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-circle-info me-2"></i>Ver Detalhes
                    </a>

                    <div>
                        <a href="consultar.php?id_localizacao=<?= htmlspecialchars($id_localizacao) ?>" class="btn btn-secondary me-2">
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