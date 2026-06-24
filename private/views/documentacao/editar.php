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
                <h2 class="text-warning fw-bold mb-1">
                    <i class="fa-solid fa-pen-to-square me-2"></i>Editar Documento
                </h2>
                <p class="text-muted mb-0">
                    Atualização dos dados do documento e da respetiva associação.
                </p>
            </div>

            <a href="documentacao.html" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <form action="documentacao.html" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_documento" value="1">

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-primary mb-1">Calibração_Vent_V500</h5>
                        <p class="text-muted mb-0">
                            <i class="fa-solid fa-file-pdf me-2"></i>
                            Documento associado ao equipamento EQP-001.
                        </p>
                    </div>

                    <div class="text-end">
                        <span class="badge bg-success mb-2">Válido</span>
                        <p class="text-muted small mb-0">calibracao_vent_v500.pdf</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold text-warning">
                    <i class="fa-solid fa-file-lines me-2"></i>1. Dados do Documento
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nome do Documento *</label>
                            <input 
                                type="text"
                                class="form-control"
                                name="nome_documento"
                                value="Calibração_Vent_V500"
                                pattern="[A-Za-z0-9À-ÿ\s\.\-_]+"
                                title="O nome só pode conter letras, números, espaços, pontos, hífen e underscore."
                                minlength="3"
                                maxlength="100"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo de Documento *</label>
                            <select class="form-select" name="tipo_documento" required>
                                <option>Manual de Utilizador</option>
                                <option>Manual de Serviço</option>
                                <option selected>Certificado</option>
                                <option>Contrato</option>
                                <option>Fatura</option>
                                <option>Declaração de Conformidade</option>
                                <option>Relatório Técnico</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Estado *</label>
                            <select class="form-select" name="estado" required>
                                <option selected>Válido</option>
                                <option>A Terminar</option>
                                <option>Expirado</option>
                                <option>Sem Validade</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Data de Emissão</label>
                            <input type="date" class="form-control" name="data_emissao" value="2026-05-20">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Data de Validade</label>
                            <input type="date" class="form-control" name="data_validade" value="2027-05-20">
                        </div>

                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold text-warning">
                    <i class="fa-solid fa-link me-2"></i>2. Associação
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo de Associação *</label>
                            <select class="form-select" name="tipo_associacao" required>
                                <option selected>Equipamento</option>
                                <option>Fornecedor</option>
                                <option>Garantia</option>
                                <option>Contrato</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Entidade Associada *</label>
                            <select class="form-select" name="entidade_associada" required>
                                <option selected>EQP-001 | Ventilador Pulmonar</option>
                                <option>EQP-002 | Monitor Philips</option>
                                <option>EQP-003 | Bomba de Infusão</option>
                                <option>EQP-004 | Desfibrilhador</option>
                                <option>Siemens Healthineers</option>
                                <option>Dräger Portugal</option>
                                <option>Contrato EQP-004</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold text-warning">
                    <i class="fa-solid fa-upload me-2"></i>3. Ficheiro e Observações
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ficheiro atual</label>
                            <input 
                                type="text"
                                class="form-control"
                                value="calibracao_vent_v500.pdf"
                                readonly
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Substituir ficheiro</label>
                            <input 
                                type="file"
                                class="form-control"
                                name="ficheiro_documento"
                                accept="pdf"
                                onchange="if(this.files[0] && this.files[0].size > 10485760){ alert('O ficheiro não pode ter mais de 10 MB.'); this.value=''; }"
                            >
                            <div class="form-text">
                                Deixe vazio se não quiser alterar. Máximo 10 MB.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Código / Referência</label>
                            <input 
                                type="text"
                                class="form-control"
                                name="referencia"
                                value="DOC-2026-001"
                                pattern="[A-Za-z0-9\-]+"
                                title="A referência só pode conter letras, números e hífen."
                                maxlength="30"
                            >
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Observações</label>
                            <textarea 
                                class="form-control"
                                name="observacoes"
                                rows="4"
                                maxlength="500"
                            >Certificado de calibração associado ao ventilador pulmonar V500.</textarea>
                            <div class="form-text">Máximo de 500 caracteres.</div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="detalhes_documento.html?id=1" class="btn btn-outline-info">
                    <i class="fa-solid fa-eye me-2"></i>Ver Detalhes
                </a>

                <div>
                    <a href="documentacao.html" class="btn btn-secondary me-2">
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
   <script src="../../../assets/js/1240881.js"></script> 
    
</body>
</html>