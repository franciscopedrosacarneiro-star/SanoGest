<?php

require_once __DIR__ . '/../../includes/funcoes.php';

redirect_if_not_logged();

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Documentação</title>
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
                <li><a href="../documentacao/documentacao.html" class="nav-link text-dark"><i class="fa-solid fa-file-contract me-2"></i>Documentação</a></li>
                
            </ul>
        </div>
    </nav>
     ciação</th>
             <th>Ações</th>
                </tr>
            </thead>
            <tbody>

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

            <a href="documentacao.html" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-primary mb-1">Calibração_Vent_V500</h5>
                    <p class="text-muted mb-0">
                        <i class="fa-solid fa-file-pdf me-2"></i>
                        Certificado associado ao equipamento EQP-001.
                    </p>
                </div>

                <div class="text-end">
                    <span class="badge bg-success mb-2">Válido</span>
                    <p class="text-muted small mb-0">Validade até 2027-05-20</p>
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
                                <td>Calibração_Vent_V500</td>
                            </tr>
                            <tr>
                                <th>Tipo:</th>
                                <td><span class="badge bg-primary">Certificado</span></td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td><span class="badge bg-success">Válido</span></td>
                            </tr>
                            <tr>
                                <th>Referência:</th>
                                <td>DOC-2026-001</td>
                            </tr>
                            <tr>
                                <th>Data de emissão:</th>
                                <td>2026-05-20</td>
                            </tr>
                            <tr>
                                <th>Data de validade:</th>
                                <td>2027-05-20</td>
                            </tr>
                            <tr>
                                <th>Ficheiro:</th>
                                <td>calibracao_vent_v500.pdf</td>
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
                                <td>Equipamento</td>
                            </tr>
                            <tr>
                                <th>Entidade associada:</th>
                                <td>EQP-001 | Ventilador Pulmonar</td>
                            </tr>
                            <tr>
                                <th>Fornecedor:</th>
                                <td>Siemens Healthineers</td>
                            </tr>
                            <tr>
                                <th>Categoria:</th>
                                <td>Suporte de Vida</td>
                            </tr>
                            <tr>
                                <th>Criticidade:</th>
                                <td><span class="badge bg-danger">Suporte de Vida</span></td>
                            </tr>
                            <tr>
                                <th>Localização:</th>
                                <td>Edifício A | Bloco Operatório | Sala 04</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold text-primary">
                <i class="fa-solid fa-download me-2"></i>Download do Documento
            </div>

            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fa-solid fa-circle-info me-2"></i>
                    Clique no botão abaixo para descarregar o documento associado.
                </div>

                <div class="d-flex justify-content-between align-items-center border rounded p-3">
                    <div>
                        <h6 class="fw-bold mb-1">
                            <i class="fa-solid fa-file-pdf text-danger me-2"></i>
                            calibracao_vent_v500.pdf
                        </h6>
                        <small class="text-muted">Formato PDF | Documento técnico de calibração</small>
                    </div>

                    <a 
                        href="../../../assets/docs/calibracao_vent_v500.pdf" 
                        class="btn btn-primary"
                        download
                    >
                        <i class="fa-solid fa-download me-2"></i>Download
                    </a>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold text-primary">
                <i class="fa-solid fa-clipboard me-2"></i>Observações
            </div>

            <div class="card-body">
                <textarea class="form-control" rows="4" readonly>Certificado de calibração associado ao ventilador pulmonar V500. Documento obrigatório para controlo técnico e auditoria.</textarea>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="documentacao.html" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Voltar
            </a>

            
        </div>

    </div>
</main>
</div>
    
    <script src="../../../assets/js/bootstrap.bundle.min.js"></script>
   <script src="../../../assets/js/funcoes.js"></script> 
    
</body>
</html>