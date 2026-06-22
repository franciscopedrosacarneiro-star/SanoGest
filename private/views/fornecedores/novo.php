<?php

require_once __DIR__ . '/../../includes/funcoes.php';

redirect_if_not_logged();

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Novo Equipamento</title>
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
                <li><a href="../fornecedores/fornecedores.html" class="nav-link text-dark"><i class="fa-solid fa-truck-medical me-2"></i>Fornecedores</a></li>
            </ul>
        </div>
    </nav>

    <main class="p-5 fundo-dashboard conteudo-principal">
         <div class="bg-white bg-opacity-75 p-5 rounded shadow-sm">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-primary fw-bold mb-1">
                    <i class="fa-solid fa-truck-medical me-2"></i>Registar Novo Fornecedor
                </h2>
                <p class="text-muted mb-0">
                    Registo de fabricantes, distribuidores, empresas de assistência técnica e fornecedores de consumíveis.
                </p>
            </div>

            <a href="fornecedores.html" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <form id="formNovoFornecedor" action="fornecedores.html" method="post">

            <!-- Indicador dos passos -->
            <ul class="nav nav-pills nav-fill mb-4">
                <li class="nav-item">
                    <button class="nav-link active" type="button" data-passo-fornecedor="1">
                        1. Empresa
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" type="button" data-passo-fornecedor="2">
                        2. Contactos
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" type="button" data-passo-fornecedor="3">
                        3. Morada e Relação
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" type="button" data-passo-fornecedor="4">
                        4. Observações
                    </button>
                </li>
            </ul>

            <!-- PASSO 1 -->
            <section class="passo-fornecedor" id="passoFornecedor1">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-building me-2"></i>Dados da Empresa
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nome da Empresa *</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="nome_empresa" 
                                    placeholder="Ex: Siemens Healthineers"
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
                                    placeholder="Ex: 500123456"
                                    pattern="[0-9]{9}"
                                    title="O NIF deve conter exatamente 9 números."
                                    required
                                >
                                <div class="form-text">Exatamente 9 números.</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Estado *</label>
                                <select class="form-select" name="estado" required>
                                    <option value="">Selecione...</option>
                                    <option selected>Ativo</option>
                                    <option>Inativo</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tipo de Fornecedor *</label>
                                <select class="form-select" name="tipo_fornecedor" required>
                                    <option value="">Selecione...</option>
                                    <option>Fabricante</option>
                                    <option>Distribuidor / Fornecedor Comercial</option>
                                    <option>Empresa de Assistência Técnica</option>
                                    <option>Fornecedor de Consumíveis/Acessórios</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Área de Atuação</label>
                                <select class="form-select" name="area_atuacao">
                                    <option value="">Selecione...</option>
                                    <option>Diagnóstico e Imagiologia</option>
                                    <option>Monitorização</option>
                                    <option>Suporte de Vida</option>
                                    <option>Laboratório</option>
                                    <option>Consumíveis Hospitalares</option>
                                    <option>Assistência Técnica</option>
                                    <option>Outro</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <!-- PASSO 2 -->
            <section class="passo-fornecedor d-none" id="passoFornecedor2">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
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
                                    placeholder="Ex: geral@empresa.pt"
                                    required
                                >
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Telefone da Empresa</label>
                                <input 
                                    type="tel" 
                                    class="form-control" 
                                    name="telefone" 
                                    placeholder="Ex: 210000000"
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
                                    placeholder="https://www.exemplo.pt"
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Pessoa de Contacto</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="pessoa_contacto" 
                                    placeholder="Ex: Ana Martins"
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
                                    placeholder="Ex: 910000000"
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
            <section class="passo-fornecedor d-none" id="passoFornecedor3">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
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
                                    placeholder="Ex: Av. da Liberdade, Lisboa"
                                    pattern="[A-Za-z0-9À-ÿ\s\.\,\-ºª]+"
                                    title="A morada só pode conter letras, números, espaços, vírgulas, pontos, hífen, º e ª."
                                    maxlength="120"
                                >
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Contrato Ativo</label>
                                <select class="form-select" name="contrato_ativo">
                                    <option value="">Selecione...</option>
                                    <option>Sim</option>
                                    <option>Não</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tipo de Relação com o Hospital</label>
                                <select class="form-select" name="relacao_hospital">
                                    <option value="">Selecione...</option>
                                    <option>Fornecedor principal</option>
                                    <option>Fornecedor secundário</option>
                                    <option>Prestador de assistência técnica</option>
                                    <option>Fornecedor de consumíveis</option>
                                    <option>Fabricante sem contrato direto</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Prioridade de Contacto</label>
                                <select class="form-select" name="prioridade_contacto">
                                    <option value="">Selecione...</option>
                                    <option>Normal</option>
                                    <option>Alta</option>
                                    <option>Urgente</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <!-- PASSO 4 -->
            <section class="passo-fornecedor d-none" id="passoFornecedor4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold text-primary">
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
                                placeholder="Indique informações adicionais sobre o fornecedor, contratos, disponibilidade ou área de atuação."
                            ></textarea>
                            <div class="form-text">Máximo de 500 caracteres.</div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fa-solid fa-circle-info me-2"></i>
                            Confirme se os dados principais, contactos e tipo de fornecedor foram preenchidos corretamente antes de guardar.
                        </div>
                    </div>
                </div>
            </section>

            <!-- Botões -->
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" id="btnAnteriorFornecedor" disabled>
                    <i class="fa-solid fa-arrow-left me-2"></i>Anterior
                </button>

                <div>
                    <a href="fornecedores.html" class="btn btn-secondary me-2">
                        Cancelar
                    </a>

                    <button type="button" class="btn btn-primary px-4" id="btnSeguinteFornecedor">
                        Seguinte
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </button>

                    <button type="submit" class="btn btn-success px-4 d-none" id="btnGuardarFornecedor">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Fornecedor
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