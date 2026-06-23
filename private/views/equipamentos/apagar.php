<?php

require_once __DIR__ . '/../../includes/funcoes.php';
require_once __DIR__ . '/../../includes/database.php';
redirect_if_not_logged();
$id_equipamento = $_GET['id_equipamento'] ?? null;

if (empty($id_equipamento) || !is_numeric($id_equipamento)) {
    header('Location: equipamentos.php');
    exit;
}

$sql = "SELECT * FROM equipamentos WHERE id_equipamento = :id_equipamento LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id_equipamento', $id_equipamento, PDO::PARAM_INT);
$stmt->execute();

$equipamento = $stmt->fetch();

if (!$equipamento) {
    header('Location: equipamentos.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "UPDATE equipamentos
                SET estado = 'Abatido'
                WHERE id_equipamento = :id_equipamento";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id_equipamento', $id_equipamento, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: consultar.php?id_equipamento=' . $id_equipamento);
        exit;
    } catch (PDOException $e) {
        $erro = 'Não foi possível abater o equipamento.';
    }
}

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanoGest | Apagar Equipamento</title>
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
            <ul class="nav nav-pills flex-column mb-auto">
                
                <li><a href="../equipamentos/equipamentos.php" class="nav-link text-dark"><i class="fa-solid fa-microchip me-2"></i>Equipamentos</a></li>
               
            </ul>
        </div>
    </nav>

    <main class="p-5 fundo-dashboard conteudo-principal">
          <div class="bg-white bg-opacity-75 p-5 rounded shadow-sm text-center">

        <div class="text-danger mb-3">
            <i class="fa-solid fa-triangle-exclamation fa-4x"></i>
        </div>

        <h2 class="text-danger mb-3">Confirmar Remoção</h2>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger text-start">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <p class="mb-4">
            Tem a certeza que pretende abater o equipamento
            <strong>
                <?= htmlspecialchars($equipamento->codigo_inventario) ?> -
                <?= htmlspecialchars($equipamento->designacao) ?>
            </strong>?
            <br>
            O equipamento continuará visível para consulta, mas deixará de poder ser editado.
        </p>

        <div class="card shadow-sm border-0 mb-4 text-start">
            <div class="card-header bg-light fw-bold">
                <i class="fa-solid fa-microchip me-2 text-primary"></i>
                Dados do equipamento
            </div>

            <div class="card-body">
                <p><strong>Código:</strong> <?= htmlspecialchars($equipamento->codigo_inventario) ?></p>
                <p><strong>Designação:</strong> <?= htmlspecialchars($equipamento->designacao) ?></p>
                <p><strong>Categoria:</strong> <?= htmlspecialchars($equipamento->categoria) ?></p>
                <p><strong>Marca/Modelo:</strong> <?= htmlspecialchars($equipamento->marca) ?> / <?= htmlspecialchars($equipamento->modelo) ?></p>
                <p><strong>N.º Série:</strong> <?= htmlspecialchars($equipamento->num_serie) ?></p>
                <p><strong>Estado:</strong> <?= htmlspecialchars($equipamento->estado) ?></p>
                <p><strong>Criticidade:</strong> <?= htmlspecialchars($equipamento->criticidade) ?></p>
            </div>
        </div>

        <form action="#" method="post">
            <div class="d-flex justify-content-center gap-3">
                <a href="equipamentos.php" class="btn btn-secondary px-4">
                    <i class="fa-solid fa-xmark me-2"></i>Cancelar
                </a>

                <button type="submit" class="btn btn-danger px-4">
                    <i class="fa-solid fa-trash me-2"></i>Sim, Abater Equipamento
                </button>
            </div>
        </form>

    </div>

        </main>
</div>
    
    <script src="../../../assets/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>