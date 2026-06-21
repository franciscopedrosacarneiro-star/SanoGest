<?php

require_once __DIR__ . '/../../includes/funcoes.php';

redirect_if_not_logged();

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Editar Página Pública</title>

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
            <ul class="nav nav-pills flex-column mb-auto">

                <li>
                    <a href="../dashboard/dashboard.html" class="nav-link text-dark">
                        <i class="fa-solid fa-gauge me-2"></i>Dashboard
                    </a>
                </li>

                <li>
                    <a href="../equipamentos/equipamentos.html" class="nav-link text-dark">
                        <i class="fa-solid fa-microchip me-2"></i>Equipamentos
                    </a>
                </li>

                <li>
                    <a href="../fornecedores/fornecedores.html" class="nav-link text-dark">
                        <i class="fa-solid fa-truck-medical me-2"></i>Fornecedores
                    </a>
                </li>

                <li>
                    <a href="../localizacoes/localizacoes.html" class="nav-link text-dark">
                        <i class="fa-solid fa-map-location-dot me-2"></i>Localizações
                    </a>
                </li>

                <li>
                    <a href="../garantias/garantias.html" class="nav-link text-dark">
                        <i class="fa-solid fa-shield-halved me-2"></i>Garantias
                    </a>
                </li>

                <li>
                    <a href="../documentacao/documentacao.html" class="nav-link text-dark">
                        <i class="fa-solid fa-file-contract me-2"></i>Documentação
                    </a>
                </li>

                <li>
                    <a href="editar-index.html" class="nav-link active">
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
                        <i class="fa-solid fa-pen-to-square me-2"></i>Editar Página Pública
                    </h2>
                    <p class="text-muted mb-0">
                        Alteração das informações apresentadas no index público do SanoGest.
                    </p>
                </div>

                <a href="../../../public/index.html" class="btn btn-outline-primary" target="_blank">
                    <i class="fa-solid fa-up-right-from-square me-2"></i>Ver Página Pública
                </a>
            </div>

            <form action="../../../public/index.html" method="post" enctype="multipart/form-data">

                <!-- Secção Hero -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-house-medical me-2"></i>1. Secção Principal
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nome do Sistema *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="nome_sistema"
                                    value="SanoGest"
                                    pattern="[A-Za-z0-9À-ÿ\s\-]+"
                                    title="O nome só pode conter letras, números, espaços e hífen."
                                    minlength="3"
                                    maxlength="40"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Slogan / Badge *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="slogan"
                                    value="Inventário Hospitalar Digital"
                                    pattern="[A-Za-z0-9À-ÿ\s\-]+"
                                    title="O slogan só pode conter letras, números, espaços e hífen."
                                    minlength="5"
                                    maxlength="80"
                                    required
                                >
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Título Principal *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="titulo_principal"
                                    value="Gestão Inteligente de Equipamentos Hospitalares"
                                    pattern="[A-Za-z0-9À-ÿ\s\.\,\-]+"
                                    title="O título só pode conter letras, números, espaços, pontos, vírgulas e hífen."
                                    minlength="10"
                                    maxlength="120"
                                    required
                                >
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Texto Principal *</label>
                                <textarea 
                                    class="form-control"
                                    name="texto_principal"
                                    rows="4"
                                    minlength="20"
                                    maxlength="500"
                                    required
                                >O SanoGest é uma plataforma digital de apoio à gestão do inventário hospitalar, permitindo centralizar informação sobre equipamentos médicos, localizações, fornecedores, documentação, garantias e contratos.</textarea>
                                <div class="form-text">Entre 20 e 500 caracteres.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Texto do Botão Principal *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="botao_principal"
                                    value="Entrar no Sistema"
                                    pattern="[A-Za-z0-9À-ÿ\s]+"
                                    title="O texto do botão só pode conter letras, números e espaços."
                                    minlength="3"
                                    maxlength="40"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Imagem de Capa</label>
                                <input 
                                    type="file"
                                    class="form-control"
                                    name="imagem_capa"
                                    accept=".jpg,.jpeg,.png,.webp"
                                    onchange="if(this.files[0] && this.files[0].size > 5242880){ alert('A imagem não pode ter mais de 5 MB.'); this.value=''; }"
                                >
                                <div class="form-text">Formatos permitidos: JPG, JPEG, PNG ou WEBP. Máximo 5 MB.</div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Secção Sobre -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-circle-info me-2"></i>2. Sobre o SanoGest
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label fw-bold">Título da Secção *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="titulo_sobre"
                                    value="Sobre o SanoGest"
                                    pattern="[A-Za-z0-9À-ÿ\s\-]+"
                                    minlength="5"
                                    maxlength="80"
                                    required
                                >
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Texto Sobre *</label>
                                <textarea 
                                    class="form-control"
                                    name="texto_sobre"
                                    rows="5"
                                    minlength="30"
                                    maxlength="700"
                                    required
                                >O SanoGest foi desenvolvido para apoiar unidades hospitalares na organização e consulta de informação relacionada com equipamentos médicos. A plataforma permite melhorar o controlo dos recursos, facilitar a consulta de dados técnicos e apoiar processos de manutenção, auditoria e gestão documental.</textarea>
                                <div class="form-text">Entre 30 e 700 caracteres.</div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Funcionalidades -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-list-check me-2"></i>3. Funcionalidades
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Funcionalidade 1 *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="funcionalidade_1"
                                    value="Gestão de Equipamentos"
                                    pattern="[A-Za-z0-9À-ÿ\s\-]+"
                                    minlength="3"
                                    maxlength="80"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Descrição 1 *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="descricao_funcionalidade_1"
                                    value="Registo e consulta de equipamentos médicos hospitalares."
                                    minlength="10"
                                    maxlength="160"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Funcionalidade 2 *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="funcionalidade_2"
                                    value="Localizações"
                                    pattern="[A-Za-z0-9À-ÿ\s\-]+"
                                    minlength="3"
                                    maxlength="80"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Descrição 2 *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="descricao_funcionalidade_2"
                                    value="Associação dos equipamentos a edifícios, pisos, serviços e salas."
                                    minlength="10"
                                    maxlength="160"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Funcionalidade 3 *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="funcionalidade_3"
                                    value="Documentação"
                                    pattern="[A-Za-z0-9À-ÿ\s\-]+"
                                    minlength="3"
                                    maxlength="80"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Descrição 3 *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="descricao_funcionalidade_3"
                                    value="Gestão de manuais, certificados, contratos e relatórios técnicos."
                                    minlength="10"
                                    maxlength="160"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Funcionalidade 4 *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="funcionalidade_4"
                                    value="Garantias e Contratos"
                                    pattern="[A-Za-z0-9À-ÿ\s\-]+"
                                    minlength="3"
                                    maxlength="80"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Descrição 4 *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="descricao_funcionalidade_4"
                                    value="Controlo de garantias, contratos de manutenção e datas de validade."
                                    minlength="10"
                                    maxlength="160"
                                    required
                                >
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Vantagens -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-star me-2"></i>4. Vantagens
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Vantagem 1 *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="vantagem_1"
                                    value="Organização centralizada"
                                    minlength="5"
                                    maxlength="80"
                                    required
                                >
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Vantagem 2 *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="vantagem_2"
                                    value="Consulta rápida de informação"
                                    minlength="5"
                                    maxlength="80"
                                    required
                                >
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Vantagem 3 *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="vantagem_3"
                                    value="Apoio à manutenção e auditoria"
                                    minlength="5"
                                    maxlength="80"
                                    required
                                >
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Rodapé -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-align-left me-2"></i>5. Rodapé
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Texto do Rodapé *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    name="texto_rodape"
                                    value="SanoGest - Sistema de Gestão Hospitalar"
                                    minlength="5"
                                    maxlength="100"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ano *</label>
                                <input 
                                    type="number"
                                    class="form-control"
                                    name="ano_rodape"
                                    value="2026"
                                    min="2020"
                                    max="2100"
                                    required
                                >
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="../../../public/index.html" class="btn btn-outline-primary" target="_blank">
                        <i class="fa-solid fa-eye me-2"></i>Pré-visualizar Página Pública
                    </a>

                    <div>
                        <a href="../dashboard/dashboard.html" class="btn btn-secondary me-2">
                            Cancelar
                        </a>

                        <button type="reset" class="btn btn-outline-warning me-2">
                            <i class="fa-solid fa-rotate-left me-2"></i>Repor Dados
                        </button>

                        <button type="submit" class="btn btn-primary px-4">
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