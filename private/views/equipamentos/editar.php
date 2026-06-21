<?php

require_once __DIR__ . '/../../includes/funcoes.php';

redirect_if_not_logged();

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Editar Equipamento</title>
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
                            <a class="dropdown-item py-2" href="../../../private/views/perfil/alterar-senha.html">
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
            <h5 class="text-primary fw-bold mb-4">Módulos</h5>
            <ul class="nav nav-pills flex-column mb-auto">
                <li><a href="../equipamentos/equipamentos.html" class="nav-link text-dark"><i class="fa-solid fa-microchip me-2"></i>Equipamentos</a></li>
            </ul>
        </div>
    </nav>

    <main class="p-5 fundo-dashboard conteudo-principal">
        <div class="bg-white bg-opacity-75 p-5 rounded shadow-sm">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-warning fw-bold mb-1">
                    <i class="fa-solid fa-pen-to-square me-2"></i>Editar Equipamento: EQP-001
                </h2>
                <p class="text-muted mb-0">
                    Atualização dos dados técnicos, administrativos e documentais do equipamento.
                </p>
            </div>

            <a href="equipamentos.html" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <form action="equipamentos.html" method="post">

            <!-- Separadores -->
            <ul class="nav nav-tabs mb-4" id="tabsEditarEquipamento" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="identificacao-tab" data-bs-toggle="tab" data-bs-target="#identificacao" type="button" role="tab">
                        <i class="fa-solid fa-microchip me-2"></i>Identificação
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="aquisicao-tab" data-bs-toggle="tab" data-bs-target="#aquisicao" type="button" role="tab">
                        <i class="fa-solid fa-cart-shopping me-2"></i>Aquisição e Estado
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="localizacao-tab" data-bs-toggle="tab" data-bs-target="#localizacao" type="button" role="tab">
                        <i class="fa-solid fa-location-dot me-2"></i>Localização
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="documentacao-tab" data-bs-toggle="tab" data-bs-target="#documentacao" type="button" role="tab">
                        <i class="fa-solid fa-file-contract me-2"></i>Documentação
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="observacoes-tab" data-bs-toggle="tab" data-bs-target="#observacoes" type="button" role="tab">
                        <i class="fa-solid fa-clipboard me-2"></i>Observações
                    </button>
                </li>
            </ul>

            <div class="tab-content">

                <!-- Identificação -->
                <div class="tab-pane fade show active" id="identificacao" role="tabpanel">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white fw-bold">
                            Dados de Identificação do Equipamento
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Código Interno *</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="codigo_inventario" 
                                        value="001"
                                        pattern="[0-9]+"
                                        title="O código deve conter apenas números."
                                        required
                                    >
                                    <div class="form-text">Apenas números.</div>
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label fw-bold">Designação *</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="designacao" 
                                        value="Ventilador Pulmonar Dräger Evita V500"
                                        minlength="3"
                                        maxlength="80"
                                        required
                                    >
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Categoria *</label>
                                    <select class="form-select" name="categoria" required>
                                        <option>Monitorização</option>
                                        <option selected>Suporte de Vida</option>
                                        <option>Terapia</option>
                                        <option>Diagnóstico</option>
                                        <option>Laboratório</option>
                                        <option>Esterilização</option>
                                        <option>Reabilitação</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Marca *</label>
                                    <input type="text" class="form-control" name="marca" value="Dräger" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Modelo *</label>
                                    <input type="text" class="form-control" name="modelo" value="Evita V500" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Nº de Série *</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="num_serie" 
                                        value="DR-99887766"
                                        pattern="[A-Za-z0-9\-]+"
                                        title="Use apenas letras, números ou hífen."
                                        required
                                    >
                                    <div class="form-text">Letras, números ou hífen.</div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Fabricante *</label>
                                    <input type="text" class="form-control" name="fabricante" value="Drägerwerk AG" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aquisição e Estado -->
                <div class="tab-pane fade" id="aquisicao" role="tabpanel">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white fw-bold">
                            Aquisição, Estado Operacional e Criticidade
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Data de Aquisição *</label>
                                    <input type="date" class="form-control" name="data_aquisicao" value="2024-03-15" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Ano de Fabrico *</label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        name="ano_fabrico" 
                                        value="2024"
                                        min="1990"
                                        max="2026"
                                        required
                                    >
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Custo de Aquisição (€) *</label>
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        min="0"
                                        class="form-control" 
                                        name="custo" 
                                        value="12500.00"
                                        required
                                    >
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Tipo de Entrada *</label>
                                    <select class="form-select" name="tipo_entrada" required>
                                        <option selected>Compra</option>
                                        <option>Doação</option>
                                        <option>Aluguer</option>
                                        <option>Empréstimo</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Estado Atual *</label>
                                    <select class="form-select" name="estado" required>
                                        <option selected>Operacional</option>
                                        <option>Em Manutenção</option>
                                        <option>Inativo</option>
                                        <option>Abatido</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Criticidade *</label>
                                    <select class="form-select" name="criticidade" required>
                                        <option>Baixa</option>
                                        <option>Média</option>
                                        <option>Alta</option>
                                        <option selected>Suporte de Vida</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Resumo do Estado</label>
                                    <div class="p-3 bg-light rounded border">
                                        <span class="badge bg-success me-2">Operacional</span>
                                        <span class="badge bg-danger">Suporte de Vida</span>
                                        <p class="text-muted small mb-0 mt-2">
                                            Equipamento crítico para suporte ventilatório. Deve ter documentação e manutenção atualizadas.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Localização -->
                <div class="tab-pane fade" id="localizacao" role="tabpanel">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white fw-bold">
                            Localização Física do Equipamento
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Edifício *</label>
                                    <input type="text" class="form-control" name="edificio" value="A" required>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Piso *</label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        name="piso" 
                                        value="1"
                                        min="0"
                                        max="20"
                                        required
                                    >
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Serviço/Departamento *</label>
                                    <input type="text" class="form-control" name="servico" value="Bloco Operatório" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Sala/Gabinete *</label>
                                    <input type="text" class="form-control" name="sala" value="Sala 04" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documentação -->
                <div class="tab-pane fade" id="documentacao" role="tabpanel">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white fw-bold">
                            Documentação Técnica e Administrativa
                        </div>

                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fa-solid fa-circle-info me-2"></i>
                                Nesta versão simplificada, podem ser registados nomes de ficheiros, caminhos ou ligações para documentos.
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Manual de Utilizador</label>
                                    <input type="text" class="form-control" name="doc_manual_util" value="manual_v500.pdf">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Manual de Serviço</label>
                                    <input type="text" class="form-control" name="doc_manual_serv" value="servico_v500.pdf">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Certificado de Calibração</label>
                                    <input type="text" class="form-control" name="doc_cert_calib" value="calibracao_2026.pdf">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Contrato de Manutenção</label>
                                    <input type="text" class="form-control" name="doc_contrato" value="contrato_drager.pdf">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Fatura ou Guia de Aquisição</label>
                                    <input type="text" class="form-control" name="doc_fatura" value="fatura_123.pdf">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Declaração de Conformidade</label>
                                    <input type="text" class="form-control" name="doc_conformidade" value="conformidade.pdf">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Relatório Técnico</label>
                                    <input type="text" class="form-control" name="doc_relatorio" value="relatorio_final.pdf">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Link/Caminho Geral</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="caminho_doc" 
                                        value="/docs/equipamentos/EQP-001/"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div class="tab-pane fade" id="observacoes" role="tabpanel">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white fw-bold">
                            Observações e Confirmação
                        </div>

                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Observações</label>
                                <textarea 
                                    class="form-control" 
                                    name="observacoes" 
                                    rows="5"
                                    maxlength="500"
                                >Equipamento em estado excelente. Manutenção preventiva programada para dezembro de 2026.</textarea>
                                <div class="form-text">Máximo de 500 caracteres.</div>
                            </div>

                            <div class="alert alert-warning">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                Antes de guardar, confirme se as alterações estão corretas.
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Botões finais -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="consultar.html" class="btn btn-outline-info">
                    <i class="fa-solid fa-eye me-2"></i>Ver Ficha
                </a>

                <div>
                    <a href="equipamentos.html" class="btn btn-secondary me-2">
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