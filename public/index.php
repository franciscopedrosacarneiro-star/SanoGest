<?php

require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>SanoGest - Gestão Hospitalar</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/1240881.css">
    <link rel="stylesheet" href="../assets/fontawesome/all.min.css">
    <link rel="shortcut icon" href="../assets/img/logo 125.png" type="image/png">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="#">
      <img src="../assets/img/logo 255.png" alt="Logo SanoGest" style="height: 40px;" class="me-2">
      SanoGest
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPublico">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menuPublico">
      <div class="navbar-nav ms-auto align-items-lg-center">
        <a class="nav-link" href="#sobre">Sobre</a>
        <a class="nav-link" href="#funcionalidades">Funcionalidades</a>
        <a class="nav-link" href="#vantagens">Vantagens</a>
        <a class="nav-link" href="#contactos">Contactos</a>
        <a class="btn btn-outline-primary ms-lg-3" href="login.html">
          <i class="fa-solid fa-right-to-bracket me-1"></i> Login
        </a>
      </div>
    </div>
  </div>
</nav>

    <header class="py-5 bg-light text-center">
        <div class="container">
            <div class="row align-items-center g-4">
      <div class="col-lg-6">
        <span class="badge bg-primary mb-3">Inventário Hospitalar Digital</span>
        <h1 class="display-5 text-primary fw-bold">
          Gestão inteligente de equipamentos médicos
        </h1>
        <p class="lead text-muted mt-3">
          O SanoGest centraliza equipamentos, localizações, fornecedores, documentação,
          garantias e contratos numa única plataforma de apoio à engenharia clínica.
        </p>
        <div class="mt-4">
          <a href="login.html" class="btn btn-primary btn-lg me-2">
            Entrar no Sistema
          </a>
          <a href="#funcionalidades" class="btn btn-outline-primary btn-lg">
            Ver Funcionalidades
          </a>
        </div>
      </div>

      <div class="col-lg-6 text-center">
        <div class="card shadow border-0 p-4">
          <i class="fa-solid fa-hospital-user text-primary display-3 mb-3"></i>
          <h4 class="fw-bold">Sistema Web de Apoio Hospitalar</h4>
          <p class="text-muted mb-0">
            Desenvolvido para organizar o ciclo de vida dos equipamentos médicos,
            desde a aquisição até ao arquivo ou abate.
          </p>
        </div>
      </div>
    </div> 
        </div>
            
    </header>

    <main>
        <section id="sobre" class="py-5">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-md-6">
        <h2 class="text-primary fw-bold">Sobre a SanoGest</h2>
        <p class="text-muted">
          A SanoGest é uma solução web criada para apoiar hospitais na gestão
          do inventário de equipamentos médicos, substituindo folhas Excel,
          registos manuais e documentação dispersa por uma plataforma centralizada.
        </p>
        <p class="text-muted">
          O sistema permite melhorar a rastreabilidade, reduzir inconsistências
          e facilitar o acesso rápido a informação técnica e administrativa.
        </p>
      </div>

      <div class="col-md-6">
        <div class="row g-3">
          <div class="col-6">
            <div class="card text-center p-3 shadow-sm h-100">
              <i class="fa-solid fa-microchip text-primary fs-2 mb-2"></i>
              <h5>Equipamentos</h5>
              <p class="text-muted small mb-0">Registo e consulta detalhada.</p>
            </div>
          </div>
          <div class="col-6">
            <div class="card text-center p-3 shadow-sm h-100">
              <i class="fa-solid fa-map-location-dot text-primary fs-2 mb-2"></i>
              <h5>Localizações</h5>
              <p class="text-muted small mb-0">Serviço, piso, sala e edifício.</p>
            </div>
          </div>
          <div class="col-6">
            <div class="card text-center p-3 shadow-sm h-100">
              <i class="fa-solid fa-file-contract text-primary fs-2 mb-2"></i>
              <h5>Documentos</h5>
              <p class="text-muted small mb-0">Manuais, certificados e relatórios.</p>
            </div>
          </div>
          <div class="col-6">
            <div class="card text-center p-3 shadow-sm h-100">
              <i class="fa-solid fa-shield-halved text-primary fs-2 mb-2"></i>
              <h5>Garantias</h5>
              <p class="text-muted small mb-0">Controlo de prazos e contratos.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

    
    </main>
<section id="funcionalidades" class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="text-primary fw-bold">Funcionalidades Principais</h2>
      <p class="text-muted">
        Tudo o que uma equipa de engenharia clínica precisa para gerir equipamentos médicos.
      </p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100 p-4 shadow-sm border-0">
          <i class="fa-solid fa-list-check text-primary fs-2 mb-3"></i>
          <h5 class="fw-bold">Inventário Médico</h5>
          <p class="text-muted">
            Registo de código interno, marca, modelo, número de série,
            estado, criticidade e custo de aquisição.
          </p>
        </div>
      </div>
        <div class="col-md-4">
        <div class="card h-100 p-4 shadow-sm border-0">
          <i class="fa-solid fa-truck-medical text-primary fs-2 mb-3"></i>
          <h5 class="fw-bold">Fornecedores</h5>
          <p class="text-muted">
            Gestão de fabricantes, distribuidores, empresas de assistência técnica
            e fornecedores de consumíveis.
          </p>
        </div>
        </div>
        <div class="col-md-4">
        <div class="card h-100 p-4 shadow-sm border-0">
          <i class="fa-solid fa-chart-simple text-primary fs-2 mb-3"></i>
          <h5 class="fw-bold">Dashboard</h5>
          <p class="text-muted">
            Indicadores rápidos sobre equipamentos ativos, manutenção,
            garantias expiradas e criticidade clínica.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
<section id="vantagens" class="py-5">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-5">
        <h2 class="text-primary fw-bold">Vantagens para o Hospital</h2>
        <p class="text-muted">
          Uma solução centralizada permite apoiar a decisão técnica,
          facilitar auditorias e melhorar o controlo documental.
        </p>
      </div>
      <div class="col-lg-7">
        <ul class="list-group shadow-sm">
          <li class="list-group-item">
            <i class="fa-solid fa-check text-success me-2"></i>
            Localização rápida de equipamentos críticos.
          </li>
          <li class="list-group-item">
            <i class="fa-solid fa-check text-success me-2"></i>
            Redução de dados duplicados e inconsistentes.
          </li>
          <li class="list-group-item">
            <i class="fa-solid fa-check text-success me-2"></i>
            Consulta centralizada de garantias, contratos e documentação.
          </li>
          <li class="list-group-item">
            <i class="fa-solid fa-check text-success me-2"></i>
            Melhor preparação para auditorias e certificação da qualidade.
          </li>
          <li class="list-group-item">
            <i class="fa-solid fa-check text-success me-2"></i>
            Base para futura evolução para sistema CMMS/GMAC.
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>


    <footer id="contactos" class="bg-white border-top py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 text-center text-md-start">
                    <h6 class="text-primary fw-bold mb-1">Contactos</h6>
                    <p class="text-muted small mb-0">Email: geral@sanogest.isep.pt</p>
                    <p class="text-muted small">Tel: +351 22 000 000</p>
                </div>
                <div class="col-md-4 text-center">
                    <p class="text-muted small">SanoGest © 2025/2026 | SIBDAS - LEBIOM</p>
                    <p class="text-muted small">ISEP - Instituto Superior de Engenharia do Porto</p>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <p href="#" class="text-decoration-none text-muted small me-3">Desenvolvido por: Aluno 1240881</p>
                    <p href="#" class="text-decoration-none text-muted small">Docente: Pedro Guimarães</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>