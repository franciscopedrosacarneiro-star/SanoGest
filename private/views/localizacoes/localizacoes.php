<?php

require_once __DIR__ . '/../../includes/funcoes.php';

redirect_if_not_logged();

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Localizações</title>
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
                <li><a href="../dashboard/dashboard.html" class="nav-link text-dark"><i class="fa-solid fa-gauge me-2"></i>Dashboard</a></li>
                 <li><a href="../equipamentos/equipamentos.html" class="nav-link text-dark"><i class="fa-solid fa-microchip me-2"></i>Equipamentos</a></li>
                <li><a href="../fornecedores/fornecedores.html" class="nav-link text-dark"><i class="fa-solid fa-truck-medical me-2"></i>Fornecedores</a></li>
                <li><a href="../localizacoes/localizacoes.html" class="nav-link text-dark"><i class="fa-solid fa-map-location-dot me-2"></i>Localizações</a></li>
                <li><a href="../garantias/garantias.html" class="nav-link text-dark"><i class="fa-solid fa-shield-halved me-2"></i>Garantias</a></li>
                <li><a href="../documentacao/documentacao.html" class="nav-link text-dark"><i class="fa-solid fa-file-contract me-2"></i>Documentação</a></li>
                <li>
                    <a href="../editar/editar-index.html" class="nav-link active">
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
                    <i class="fa-solid fa-map-location-dot me-2"></i>Localizações
                </h2>
                <p class="text-muted mb-0">
                    Gestão das localizações físicas dos equipamentos médicos por edifício, piso, serviço e sala.
                </p>
            </div>

            <a href="novo.html" class="btn btn-primary">
                <i class="fa-solid fa-plus me-2"></i>Nova Localização
            </a>
        </div>


        <!-- Pesquisa e filtros -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold">
                <i class="fa-solid fa-filter me-2 text-primary"></i>Pesquisa e Filtros
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-5">
                        <label for="pesquisaLocalizacao" class="form-label fw-bold">Pesquisar</label>
                        <input 
                            type="text" 
                            id="pesquisaLocalizacao" 
                            class="form-control" 
                            placeholder="Pesquisar por edifício, piso, serviço ou sala..."
                        >
                    </div>

                    <div class="col-md-3">
                        <label for="filtroEdificio" class="form-label fw-bold">Edifício</label>
                        <select id="filtroEdificio" class="form-select">
                            <option value="">Todos</option>
                            <option value="Edifício A">Edifício A</option>
                            <option value="Edifício B">Edifício B</option>
                            <option value="Edifício C">Edifício C</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="filtroPiso" class="form-label fw-bold">Piso</label>
                        <select id="filtroPiso" class="form-select">
                            <option value="">Todos</option>
                            <option value="Piso 0">Piso 0</option>
                            <option value="Piso 1">Piso 1</option>
                            <option value="Piso 2">Piso 2</option>
                            <option value="Piso 3">Piso 3</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="filtroServico" class="form-label fw-bold">Serviço</label>
                        <select id="filtroServico" class="form-select">
                            <option value="">Todos</option>
                            <option value="Bloco Operatório">Bloco Operatório</option>
                            <option value="Urgência">Urgência</option>
                            <option value="UCI">UCI</option>
                            <option value="Radiologia">Radiologia</option>
                            <option value="Laboratório">Laboratório</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>

        <!-- Tabela -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold">
                    <i class="fa-solid fa-list me-2 text-primary"></i>Lista de Localizações
                </span>
                <small class="text-muted">Localização física atual dos equipamentos hospitalares</small>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelaLocalizacoes">
                    <thead class="table-light">
                        <tr>
                            <th>Edifício</th>
                            <th>Piso</th>
                            <th>Serviço</th>
                            <th>Sala/Gabinete</th>
                            <th>Tipo de Área</th>
                            <th>Equipamentos</th>
                            <th>Estado</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr data-edificio="Edifício A" data-piso="Piso 1" data-servico="Bloco Operatório">
                            <td class="fw-bold">Edifício A</td>
                            <td>Piso 1</td>
                            <td>Bloco Operatório</td>
                            <td>Sala 04</td>
                            <td><span class="badge bg-danger">Área Crítica</span></td>
                            <td><span class="badge bg-secondary">3 Equipamentos</span></td>
                            <td><span class="badge bg-success">Ativa</span></td>
                            <td class="text-end">
                                <a href="detalhes.html" class="btn btn-sm btn-outline-info" title="Ver Equipamentos">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="editar.html" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="apagar.html" class="btn btn-sm btn-outline-danger" title="Remover">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <tr data-edificio="Edifício A" data-piso="Piso 0" data-servico="Urgência">
                            <td class="fw-bold">Edifício A</td>
                            <td>Piso 0</td>
                            <td>Urgência</td>
                            <td>Sala de Reanimação</td>
                            <td><span class="badge bg-danger">Área Crítica</span></td>
                            <td><span class="badge bg-secondary">5 Equipamentos</span></td>
                            <td><span class="badge bg-success">Ativa</span></td>
                            <td class="text-end">
                                <a href="detalhes.html" class="btn btn-sm btn-outline-info" title="Ver Equipamentos">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="editar.html" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="apagar.html" class="btn btn-sm btn-outline-danger" title="Remover">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <tr data-edificio="Edifício B" data-piso="Piso 2" data-servico="UCI">
                            <td class="fw-bold">Edifício B</td>
                            <td>Piso 2</td>
                            <td>UCI</td>
                            <td>Box 03</td>
                            <td><span class="badge bg-danger">Área Crítica</span></td>
                            <td><span class="badge bg-secondary">8 Equipamentos</span></td>
                            <td><span class="badge bg-success">Ativa</span></td>
                            <td class="text-end">
                                <a href="detalhes.html" class="btn btn-sm btn-outline-info" title="Ver Equipamentos">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="editar.html" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="apagar.html" class="btn btn-sm btn-outline-danger" title="Remover">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <tr data-edificio="Edifício B" data-piso="Piso 1" data-servico="Radiologia">
                            <td class="fw-bold">Edifício B</td>
                            <td>Piso 1</td>
                            <td>Radiologia</td>
                            <td>Sala RX 02</td>
                            <td><span class="badge bg-primary">Diagnóstico</span></td>
                            <td><span class="badge bg-secondary">4 Equipamentos</span></td>
                            <td><span class="badge bg-success">Ativa</span></td>
                            <td class="text-end">
                                <a href="detalhes.html" class="btn btn-sm btn-outline-info" title="Ver Equipamentos">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="editar.html" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="apagar.html" class="btn btn-sm btn-outline-danger" title="Remover">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <tr data-edificio="Edifício C" data-piso="Piso 3" data-servico="Laboratório">
                            <td class="fw-bold">Edifício C</td>
                            <td>Piso 3</td>
                            <td>Laboratório</td>
                            <td>Lab. Central</td>
                            <td><span class="badge bg-info text-dark">Laboratório</span></td>
                            <td><span class="badge bg-secondary">4 Equipamentos</span></td>
                            <td><span class="badge bg-success">Ativa</span></td>
                            <td class="text-end">
                                <a href="detalhes.html" class="btn btn-sm btn-outline-info" title="Ver Equipamentos">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="editar.html" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="apagar.html" class="btn btn-sm btn-outline-danger" title="Remover">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white text-muted small">
                Use a pesquisa ou os filtros para localizar rapidamente equipamentos por edifício, piso, serviço ou sala.
            </div>
        </div>

    </div>
        
        </main>
</div>
    
    <script src="../../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/1240881.js"></script>
    
</body>
</html>