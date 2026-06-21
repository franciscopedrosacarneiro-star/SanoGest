<?php

require_once __DIR__ . '/../../includes/funcoes.php';

redirect_if_not_logged();

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
                <li><a href="localizacoes.html" class="nav-link text-dark"><i class="fa-solid fa-map-location-dot me-2"></i>Localizações</a></li>
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

            <a href="localizacoes.html" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <form action="processar_editar_localizacao.php" method="POST">
            <input type="hidden" name="id" value="1">

            <!-- Resumo da localização -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-primary mb-1">
                            Edifício A | Piso 1 | Bloco Operatório
                        </h5>
                        <p class="text-muted mb-0">
                            <i class="fa-solid fa-door-open me-2"></i>
                            Sala 04 — zona atualmente ativa.
                        </p>
                    </div>

                    <div class="text-end">
                        <span class="badge bg-success mb-2">Ativa</span>
                        <p class="text-muted small mb-0">3 equipamentos associados</p>
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
                                value="Edifício A"
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
                                value="1"
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
                                value="Bloco Operatório"
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
                                value="Sala 04"
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
                            <label class="form-label fw-bold">Tipo de Área</label>
                            <select class="form-select" name="tipo_area">
                                <option>Área Crítica</option>
                                <option selected>Bloco Operatório</option>
                                <option>Diagnóstico</option>
                                <option>Internamento</option>
                                <option>Laboratório</option>
                                <option>Armazém</option>
                                <option>Administrativa</option>
                                <option>Outra</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Estado da Localização</label>
                            <select class="form-select" name="estado">
                                <option selected>Ativa</option>
                                <option>Inativa</option>
                                <option>Em obras</option>
                                <option>Indisponível</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Capacidade estimada de equipamentos</label>
                            <input 
                                type="number" 
                                class="form-control" 
                                name="capacidade_equipamentos" 
                                value="10"
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
                                name="equipamentos_associados" 
                                value="3"
                                min="0"
                                readonly
                            >
                            <div class="form-text">Campo informativo.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Acesso restrito</label>
                            <select class="form-select" name="acesso_restrito">
                                <option selected>Sim</option>
                                <option>Não</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Prioridade técnica</label>
                            <select class="form-select" name="prioridade_tecnica">
                                <option>Normal</option>
                                <option selected>Alta</option>
                                <option>Crítica</option>
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
                    >Zona de alta esterilização.</textarea>
                    <div class="form-text">Máximo de 500 caracteres.</div>
                </div>
            </div>

            <!-- Botões -->
            <div class="d-flex justify-content-between align-items-center mt-4">

                <div>
                    <a href="localizacoes.html" class="btn btn-secondary me-2">
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