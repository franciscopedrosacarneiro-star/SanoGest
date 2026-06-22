<?php

require_once __DIR__ . '/../../includes/funcoes.php';

redirect_if_not_logged();

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Detalhes da Garantia</title>
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
                
                <li><a href="../garantias/garantias.html" class="nav-link text-dark"><i class="fa-solid fa-shield-halved me-2"></i>Garantias</a></li>
            </ul>
        </div>
    </nav>
    <main class="p-5 fundo-dashboard conteudo-principal">
    <div class="bg-white bg-opacity-75 p-5 rounded shadow-sm">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-info fw-bold mb-1">
                    <i class="fa-solid fa-file-lines me-2"></i>Detalhes da Garantia/Contrato
                </h2>
                <p class="text-muted mb-0">
                    Consulta detalhada da garantia e contrato de manutenção associado ao equipamento.
                </p>
            </div>

            <a href="garantias.html" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-primary mb-1">Ventilador EQP-001</h5>
                    <p class="text-muted mb-0">
                        <i class="fa-solid fa-microchip me-2"></i>
                        Contrato preventivo associado a equipamento de suporte de vida.
                    </p>
                </div>

                <div class="text-end">
                    <span class="badge bg-success mb-2">Ativa</span>
                    <p class="text-muted small mb-0">Validade até 01/01/2028</p>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-shield-halved me-2"></i>Informação da Garantia
                    </div>

                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th>Equipamento:</th>
                                <td>Ventilador EQP-001</td>
                            </tr>
                            <tr>
                                <th>Tipo:</th>
                                <td><span class="badge bg-info text-dark">Preventiva</span></td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td><span class="badge bg-success">Ativa</span></td>
                            </tr>
                            <tr>
                                <th>Criticidade:</th>
                                <td><span class="badge bg-danger">Suporte de Vida</span></td>
                            </tr>
                            <tr>
                                <th>Data de início:</th>
                                <td>01/01/2026</td>
                            </tr>
                            <tr>
                                <th>Data de fim:</th>
                                <td>01/01/2028</td>
                            </tr>
                            <tr>
                                <th>Duração:</th>
                                <td>24 meses</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold text-primary">
                        <i class="fa-solid fa-building me-2"></i>Responsável / Fornecedor
                    </div>

                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th>Fornecedor:</th>
                                <td>Siemens Healthineers</td>
                            </tr>
                            <tr>
                                <th>NIF:</th>
                                <td>500123456</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>geral@siemens.pt</td>
                            </tr>
                            <tr>
                                <th>Telefone:</th>
                                <td>210000000</td>
                            </tr>
                            <tr>
                                <th>Pessoa de contacto:</th>
                                <td>Ana Martins</td>
                            </tr>
                            <tr>
                                <th>Prioridade:</th>
                                <td><span class="badge bg-warning text-dark">Alta</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold text-primary">
                <i class="fa-solid fa-file-contract me-2"></i>Documentação e Observações
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Documento associado</label>
                        <input type="text" class="form-control" value="contrato_eqp001.pdf" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Número do contrato</label>
                        <input type="text" class="form-control" value="CON-2026-001" readonly>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Observações</label>
                        <textarea class="form-control" rows="4" readonly>Contrato de manutenção preventiva para equipamento crítico de suporte ventilatório. Inclui revisão anual e assistência prioritária.</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="garantias.html" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Voltar
            </a>
        </div>

    </div>
</main>
</div>
    
    <script src="../../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/1240881.js"></script>
    
</body>
</html>