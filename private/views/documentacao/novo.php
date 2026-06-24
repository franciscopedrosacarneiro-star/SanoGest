<?php

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/database.php';

redirect_if_not_logged();

$erro = '';

$equipamentos = listar_equipamentos_para_documento($pdo);
$fornecedores = listar_fornecedores_para_documento($pdo);
$garantias = listar_garantias_para_documento($pdo);

$valores = (object) [
    'nome_documento' => '',
    'tipo_documento' => '',
    'estado' => 'Válido',
    'data_emissao' => '',
    'data_validade' => '',
    'tipo_associacao' => '',
    'id_entidade_associada' => '',
    'ficheiro' => '',
    'caminho_ficheiro' => '',
    'referencia' => '',
    'tamanho_bytes' => '',
    'mime_type' => '',
    'observacoes' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = recolher_dados_documento_post();
    $valores = (object) $dados;

    if (!validar_dados_documento($dados)) {
        $erro = 'Preenche corretamente todos os campos obrigatórios. Confirma também a associação, as datas e o ficheiro.';
    } else {
        try {
            $id_documento = criar_documento($pdo, $dados);

            if (!$id_documento) {
                $erro = 'Não foi possível associar o documento à entidade selecionada.';
            } else {
                redirecionar('consultar.php?id_documento=' . $id_documento);
            }
        } catch (PDOException $e) {
            $erro = 'Erro ao guardar o documento.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Novo Documento</title>

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
                    <a href="../documentacao/documentacao.php" class="nav-link text-dark">
                        <i class="fa-solid fa-file-contract me-2"></i>Documentação
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
                        <i class="fa-solid fa-plus me-2"></i>Novo Documento
                    </h2>

                    <p class="text-muted mb-0">
                        Registo de um novo documento técnico ou administrativo associado ao inventário hospitalar.
                    </p>
                </div>

                <a href="documentacao.php" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <?php if (!empty($erro)): ?>
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    <?= htmlspecialchars($erro) ?>
                </div>
            <?php endif; ?>

            <form action="novo.php" method="POST" novalidate>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-file-circle-plus me-2"></i>1. Dados do Documento
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="nome_documento" class="form-label fw-bold">Nome do Documento *</label>
                                <input 
                                    type="text"
                                    id="nome_documento"
                                    name="nome_documento"
                                    class="form-control"
                                    value="<?= htmlspecialchars($valores->nome_documento ?? '') ?>"
                                    placeholder="Ex: Calibracao_Vent_V500"
                                    minlength="3"
                                    maxlength="100"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="tipo_documento" class="form-label fw-bold">Tipo de Documento *</label>
                                <select id="tipo_documento" name="tipo_documento" class="form-select" required>
                                    <option value="">Selecionar...</option>
                                    <option <?= selecionado($valores->tipo_documento ?? '', 'Manual de Utilizador') ?>>Manual de Utilizador</option>
                                    <option <?= selecionado($valores->tipo_documento ?? '', 'Manual de Serviço') ?>>Manual de Serviço</option>
                                    <option <?= selecionado($valores->tipo_documento ?? '', 'Certificado') ?>>Certificado</option>
                                    <option <?= selecionado($valores->tipo_documento ?? '', 'Contrato') ?>>Contrato</option>
                                    <option <?= selecionado($valores->tipo_documento ?? '', 'Fatura') ?>>Fatura</option>
                                    <option <?= selecionado($valores->tipo_documento ?? '', 'Declaração de Conformidade') ?>>Declaração de Conformidade</option>
                                    <option <?= selecionado($valores->tipo_documento ?? '', 'Relatório Técnico') ?>>Relatório Técnico</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="estado" class="form-label fw-bold">Estado *</label>
                                <select id="estado" name="estado" class="form-select" required>
                                    <option <?= selecionado($valores->estado ?? '', 'Válido') ?>>Válido</option>
                                    <option <?= selecionado($valores->estado ?? '', 'A Terminar') ?>>A Terminar</option>
                                    <option <?= selecionado($valores->estado ?? '', 'Expirado') ?>>Expirado</option>
                                    <option <?= selecionado($valores->estado ?? '', 'Sem Validade') ?>>Sem Validade</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="data_emissao" class="form-label fw-bold">Data de Emissão</label>
                                <input 
                                    type="date" 
                                    id="data_emissao" 
                                    name="data_emissao" 
                                    class="form-control"
                                    value="<?= htmlspecialchars($valores->data_emissao ?? '') ?>"
                                >
                            </div>

                            <div class="col-md-4">
                                <label for="data_validade" class="form-label fw-bold">Data de Validade</label>
                                <input 
                                    type="date" 
                                    id="data_validade" 
                                    name="data_validade" 
                                    class="form-control"
                                    value="<?= htmlspecialchars($valores->data_validade ?? '') ?>"
                                >
                                <div class="form-text">Deixa em branco se o documento não tiver validade.</div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-link me-2"></i>2. Associação
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="tipo_associacao" class="form-label fw-bold">Tipo de Associação *</label>
                                <select id="tipo_associacao" name="tipo_associacao" class="form-select" required>
                                    <option value="">Selecionar...</option>
                                    <option <?= selecionado($valores->tipo_associacao ?? '', 'Equipamento') ?>>Equipamento</option>
                                    <option <?= selecionado($valores->tipo_associacao ?? '', 'Fornecedor') ?>>Fornecedor</option>
                                    <option <?= selecionado($valores->tipo_associacao ?? '', 'Garantia') ?>>Garantia</option>
                                    <option <?= selecionado($valores->tipo_associacao ?? '', 'Contrato') ?>>Contrato</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="id_entidade_associada" class="form-label fw-bold">Entidade Associada *</label>
                                <select id="id_entidade_associada" name="id_entidade_associada" class="form-select" required>
                                    <option value="">Selecionar primeiro o tipo de associação...</option>

                                    <?php foreach ($equipamentos as $equipamento): ?>
                                        <option 
                                            value="<?= htmlspecialchars($equipamento->id_equipamento) ?>"
                                            data-tipo="Equipamento"
                                            <?= selecionado((string) ($valores->id_entidade_associada ?? ''), (string) $equipamento->id_equipamento) ?>
                                        >
                                            <?= htmlspecialchars($equipamento->codigo_inventario ?? '') ?> |
                                            <?= htmlspecialchars($equipamento->designacao ?? '') ?>
                                            <?= !empty($equipamento->marca) ? ' — ' . htmlspecialchars($equipamento->marca) : '' ?>
                                            <?= !empty($equipamento->modelo) ? ' ' . htmlspecialchars($equipamento->modelo) : '' ?>
                                        </option>
                                    <?php endforeach; ?>

                                    <?php foreach ($fornecedores as $fornecedor): ?>
                                        <option 
                                            value="<?= htmlspecialchars($fornecedor->id_fornecedor) ?>"
                                            data-tipo="Fornecedor"
                                            <?= selecionado((string) ($valores->id_entidade_associada ?? ''), (string) $fornecedor->id_fornecedor) ?>
                                        >
                                            <?= htmlspecialchars($fornecedor->nome_empresa ?? '') ?>
                                            <?= !empty($fornecedor->tipo_fornecedor) ? ' — ' . htmlspecialchars($fornecedor->tipo_fornecedor) : '' ?>
                                        </option>
                                    <?php endforeach; ?>

                                    <?php foreach ($garantias as $garantia): ?>
                                        <option 
                                            value="<?= htmlspecialchars($garantia->id_garantia) ?>"
                                            data-tipo="Garantia"
                                            <?= selecionado((string) ($valores->id_entidade_associada ?? ''), (string) $garantia->id_garantia) ?>
                                        >
                                            <?= htmlspecialchars($garantia->tipo_contrato ?? '') ?> |
                                            <?= !empty($garantia->numero_contrato) ? htmlspecialchars($garantia->numero_contrato) : 'Garantia ID ' . htmlspecialchars($garantia->id_garantia) ?> |
                                            <?= htmlspecialchars($garantia->codigo_inventario ?? '') ?> |
                                            <?= htmlspecialchars($garantia->equipamento_designacao ?? '') ?>
                                        </option>

                                        <option 
                                            value="<?= htmlspecialchars($garantia->id_garantia) ?>"
                                            data-tipo="Contrato"
                                            <?= selecionado((string) ($valores->id_entidade_associada ?? ''), (string) $garantia->id_garantia) ?>
                                        >
                                            Contrato |
                                            <?= !empty($garantia->numero_contrato) ? htmlspecialchars($garantia->numero_contrato) : 'ID ' . htmlspecialchars($garantia->id_garantia) ?> |
                                            <?= htmlspecialchars($garantia->codigo_inventario ?? '') ?> |
                                            <?= htmlspecialchars($garantia->equipamento_designacao ?? '') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <div class="form-text">
                                    A lista muda consoante o tipo de associação escolhido.
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-file-arrow-up me-2"></i>3. Ficheiro
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="ficheiro" class="form-label fw-bold">Nome do ficheiro *</label>
                                <input 
                                    type="text"
                                    id="ficheiro"
                                    name="ficheiro"
                                    class="form-control"
                                    value="<?= htmlspecialchars($valores->ficheiro ?? '') ?>"
                                    placeholder="Ex: calibracao_vent_v500.pdf"
                                    maxlength="150"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="caminho_ficheiro" class="form-label fw-bold">Caminho/link do ficheiro *</label>
                                <input 
                                    type="text"
                                    id="caminho_ficheiro"
                                    name="caminho_ficheiro"
                                    class="form-control"
                                    value="<?= htmlspecialchars($valores->caminho_ficheiro ?? '') ?>"
                                    placeholder="Ex: ../../../assets/docs/calibracao_vent_v500.pdf"
                                    maxlength="200"
                                    required
                                >
                                <div class="form-text">
                                    Por agora guardamos apenas o caminho/link. O upload real pode ficar para uma melhoria futura.
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="referencia" class="form-label fw-bold">Código / Referência</label>
                                <input 
                                    type="text"
                                    id="referencia"
                                    name="referencia"
                                    class="form-control"
                                    value="<?= htmlspecialchars($valores->referencia ?? '') ?>"
                                    placeholder="Ex: DOC-2026-001"
                                    maxlength="50"
                                >
                            </div>

                            <div class="col-md-4">
                                <label for="tamanho_bytes" class="form-label fw-bold">Tamanho em bytes</label>
                                <input 
                                    type="number"
                                    id="tamanho_bytes"
                                    name="tamanho_bytes"
                                    class="form-control"
                                    value="<?= htmlspecialchars($valores->tamanho_bytes ?? '') ?>"
                                    placeholder="Ex: 1048576"
                                    min="0"
                                >
                            </div>

                            <div class="col-md-4">
                                <label for="mime_type" class="form-label fw-bold">Tipo MIME</label>
                                <input 
                                    type="text"
                                    id="mime_type"
                                    name="mime_type"
                                    class="form-control"
                                    value="<?= htmlspecialchars($valores->mime_type ?? '') ?>"
                                    placeholder="Ex: application/pdf"
                                    maxlength="80"
                                >
                            </div>

                            <div class="col-12">
                                <label for="observacoes" class="form-label fw-bold">Observações</label>
                                <textarea 
                                    id="observacoes"
                                    name="observacoes"
                                    class="form-control"
                                    rows="4"
                                    maxlength="500"
                                    placeholder="Observações sobre o documento, validade, origem ou utilização..."
                                ><?= htmlspecialchars($valores->observacoes ?? '') ?></textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="documentacao.php" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-list me-2"></i>Ver Documentação
                    </a>

                    <div>
                        <a href="documentacao.php" class="btn btn-secondary me-2">
                            Cancelar
                        </a>

                        <button type="reset" class="btn btn-outline-warning me-2">
                            <i class="fa-solid fa-rotate-left me-2"></i>Limpar
                        </button>

                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Documento
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </main>
</div>

<script src="../../../assets/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoAssociacao = document.getElementById('tipo_associacao');
    const entidadeAssociada = document.getElementById('id_entidade_associada');

    function atualizarEntidades() {
        const tipoSelecionado = tipoAssociacao.value;
        const opcoes = entidadeAssociada.querySelectorAll('option');

        opcoes.forEach(function (opcao) {
            const tipoOpcao = opcao.getAttribute('data-tipo');

            if (!tipoOpcao) {
                opcao.hidden = false;
                opcao.disabled = false;
                return;
            }

            if (tipoOpcao === tipoSelecionado) {
                opcao.hidden = false;
                opcao.disabled = false;
            } else {
                opcao.hidden = true;
                opcao.disabled = true;

                if (opcao.selected) {
                    opcao.selected = false;
                }
            }
        });

        if (!entidadeAssociada.value) {
            entidadeAssociada.selectedIndex = 0;
        }
    }

    tipoAssociacao.addEventListener('change', atualizarEntidades);
    atualizarEntidades();
});
</script>

</body>
</html>
