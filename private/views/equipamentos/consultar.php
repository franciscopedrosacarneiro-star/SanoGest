<?php

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/database.php';
redirect_if_not_logged();
$id_equipamento = $_GET['id_equipamento'] ?? null;

if (empty($id_equipamento) || !is_numeric($id_equipamento)) {
    header('Location: equipamentos.php');
    exit;
}

try {
    $sql = "SELECT * FROM vw_equipamentos_completo WHERE id_equipamento = :id_equipamento LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_equipamento', $id_equipamento, PDO::PARAM_INT);
    $stmt->execute();

    $equipamento = $stmt->fetch();
} catch (PDOException $erro) {
    $sql = "SELECT * FROM equipamentos WHERE id_equipamento = :id_equipamento LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_equipamento', $id_equipamento, PDO::PARAM_INT);
    $stmt->execute();

    $equipamento = $stmt->fetch();
}

if (!$equipamento) {
    header('Location: equipamentos.php');
    exit;
}

$sqlDocumentos = "SELECT * FROM documentos WHERE id_equipamento = :id_equipamento ORDER BY tipo_documento ASC";
$stmtDocumentos = $pdo->prepare($sqlDocumentos);
$stmtDocumentos->bindValue(':id_equipamento', $id_equipamento, PDO::PARAM_INT);
$stmtDocumentos->execute();

$documentos = $stmtDocumentos->fetchAll();

function badge_estado_equipamento($estado)
{
    return match ($estado) {
        'Operacional' => 'success',
        'Em Manutenção' => 'warning text-dark',
        'Inativo' => 'secondary',
        'Abatido' => 'dark',
        default => 'secondary'
    };
}

function badge_criticidade_equipamento($criticidade)
{
    return match ($criticidade) {
        'Baixa' => 'secondary',
        'Média' => 'info text-dark',
        'Alta' => 'warning text-dark',
        'Suporte de Vida' => 'danger',
        default => 'secondary'
    };
}

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Consultar Equipamento</title>
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
            <h5 class="text-primary fw-bold mb-4">Módulos</h5>
            <ul class="nav nav-pills flex-column mb-auto">
                <li><a href="../equipamentos/equipamentos.php" class="nav-link text-dark"><i class="fa-solid fa-microchip me-2"></i>Equipamentos</a></li>
            </ul>
        </div>
     </nav>

     <main class="p-5 fundo-dashboard conteudo-principal">
           <div class="bg-white bg-opacity-75 p-5 rounded shadow-sm"> 

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary">
                <i class="fa-solid fa-circle-info me-2"></i>
                Detalhes do Equipamento: <?= htmlspecialchars($equipamento->codigo_inventario) ?>
            </h2>

            <div>
                <a href="equipamentos.php" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Voltar
                </a>

               <?php if (($equipamento->estado ?? '') !== 'Abatido'): ?>
    <a href="editar.php?id_equipamento=<?= $equipamento->id_equipamento ?>" class="btn btn-warning">
        <i class="fa-solid fa-pen me-2"></i>Editar
    </a>
<?php else: ?>
    <button class="btn btn-secondary" disabled>
        <i class="fa-solid fa-ban me-2"></i>Equipamento Abatido
    </button>
    <?php endif; ?>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-light fw-bold">
                        Informações Técnicas
                    </div>

                    <div class="card-body">
                        <p><strong>Designação:</strong> <?= htmlspecialchars($equipamento->designacao ?? '') ?></p>
                        <p><strong>Categoria:</strong> <?= htmlspecialchars($equipamento->categoria ?? '') ?></p>
                        <p><strong>Marca:</strong> <?= htmlspecialchars($equipamento->marca ?? '') ?></p>
                        <p><strong>Modelo:</strong> <?= htmlspecialchars($equipamento->modelo ?? '') ?></p>
                        <p><strong>N.º Série:</strong> <?= htmlspecialchars($equipamento->num_serie ?? '') ?></p>
                        <p><strong>Fabricante:</strong> <?= htmlspecialchars($equipamento->fabricante ?? '') ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-light fw-bold">
                        Gestão e Aquisição
                    </div>

                    <div class="card-body">
                        <p>
                            <strong>Data de Aquisição:</strong>
                            <?= !empty($equipamento->data_aquisicao) ? date('d/m/Y', strtotime($equipamento->data_aquisicao)) : '—' ?>
                        </p>

                        <p><strong>Ano de Fabrico:</strong> <?= htmlspecialchars($equipamento->ano_fabrico ?? '—') ?></p>
                        <p><strong>Tipo de Entrada:</strong> <?= htmlspecialchars($equipamento->tipo_entrada ?? '—') ?></p>

                        <p>
                            <strong>Custo:</strong>
                            <?= isset($equipamento->custo) ? number_format($equipamento->custo, 2, ',', ' ') . ' €' : '—' ?>
                        </p>

                        <p>
                            <strong>Estado:</strong>
                            <span class="badge bg-<?= badge_estado_equipamento($equipamento->estado ?? '') ?>">
                                <?= htmlspecialchars($equipamento->estado ?? '') ?>
                            </span>
                        </p>

                        <p>
                            <strong>Criticidade:</strong>
                            <span class="badge bg-<?= badge_criticidade_equipamento($equipamento->criticidade ?? '') ?>">
                                <?= htmlspecialchars($equipamento->criticidade ?? '') ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light fw-bold">
                        Localização Física
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Edifício:</strong>
                                <?= htmlspecialchars($equipamento->edificio ?? '—') ?>
                            </div>

                            <div class="col-md-3">
                                <strong>Piso:</strong>
                                <?= htmlspecialchars($equipamento->piso ?? '—') ?>
                            </div>

                            <div class="col-md-3">
                                <strong>Serviço:</strong>
                                <?= htmlspecialchars($equipamento->servico ?? '—') ?>
                            </div>

                            <div class="col-md-3">
                                <strong>Sala:</strong>
                                <?= htmlspecialchars($equipamento->sala ?? '—') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card mt-4 shadow-sm border-0">
            <div class="card-header bg-light fw-bold">
                Documentação do Equipamento
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tipo de Documento</th>
                                <th>Ficheiro</th>
                                <th>Estado</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (empty($documentos)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fa-solid fa-circle-info me-2"></i>
                                        Não existem documentos associados a este equipamento.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($documentos as $documento): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($documento->tipo_documento ?? '') ?></td>
                                        <td><?= htmlspecialchars($documento->ficheiro ?? $documento->nome_documento ?? '') ?></td>
                                        <td><?= htmlspecialchars($documento->estado ?? '') ?></td>
                                        <td class="text-end">
                                            <?php if (!empty($documento->caminho_ficheiro)): ?>
                                                <a href="../../../<?= htmlspecialchars($documento->caminho_ficheiro) ?>" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   download>
                                                    <i class="fa-solid fa-download"></i>
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                                    <i class="fa-solid fa-download"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h5>Observações</h5>

            <div class="p-3 bg-light rounded border">
                <?= !empty($equipamento->observacoes) ? nl2br(htmlspecialchars($equipamento->observacoes)) : 'Sem observações registadas.' ?>
            </div>
        </div>


        </main>
</div>
    
    <script src="../../../assets/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>