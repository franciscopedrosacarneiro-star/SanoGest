
<?php

require_once __DIR__ . '/../../config/config.php';

// =====================================================
// SESSÕES E AUTENTICAÇÃO
// =====================================================

function start_session()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function check_session()
{
    start_session();

    return isset($_SESSION['utilizador']);
}

function redirect_if_not_logged()
{
    start_session();

    if (!check_session()) {
        header('Location: ' . BASE_URL . '/public/login.php');
        exit;
    }
}

function redirect_if_logged()
{
    start_session();

    if (check_session()) {
        header('Location: ' . BASE_URL . '/private/index.php');
        exit;
    }
}

function logout()
{
    start_session();

    session_unset();
    session_destroy();

    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}


// =====================================================
// FUNÇÕES GERAIS AUXILIARES
// =====================================================

function limpar_texto($valor)
{
    return trim(htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8'));
}

function redirecionar($pagina)
{
    header('Location: ' . $pagina);
    exit;
}

function validar_id($id)
{
    return !empty($id) && is_numeric($id);
}

function selecionado($valor_atual, $valor_opcao)
{
    return $valor_atual === $valor_opcao ? 'selected' : '';
}


// =====================================================
// BADGES / ESTADOS VISUAIS
// =====================================================

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

function equipamento_abatido($equipamento)
{
    return ($equipamento->estado ?? '') === 'Abatido';
}


// =====================================================
// EQUIPAMENTOS — LISTAGEM E CONSULTA
// =====================================================

function listar_equipamentos($pdo)
{
    try {
        $sql = "SELECT * FROM vw_equipamentos_completo ORDER BY id_equipamento DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $erro) {
        $sql = "SELECT * FROM equipamentos ORDER BY id_equipamento DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }
}

function buscar_equipamento_por_id($pdo, $id_equipamento)
{
    if (!validar_id($id_equipamento)) {
        return false;
    }

    try {
        $sql = "SELECT * FROM vw_equipamentos_completo
                WHERE id_equipamento = :id_equipamento
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id_equipamento', $id_equipamento, PDO::PARAM_INT);
        $stmt->execute();

        $equipamento = $stmt->fetch();

        if ($equipamento) {
            return $equipamento;
        }
    } catch (PDOException $erro) {
        // Se a view não existir ou falhar, tenta buscar diretamente na tabela equipamentos.
    }

    $sql = "SELECT * FROM equipamentos
            WHERE id_equipamento = :id_equipamento
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_equipamento', $id_equipamento, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch();
}

function buscar_documentos_do_equipamento($pdo, $id_equipamento)
{
    if (!validar_id($id_equipamento)) {
        return [];
    }

    $sql = "SELECT * FROM documentos
            WHERE id_equipamento = :id_equipamento
            ORDER BY tipo_documento ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_equipamento', $id_equipamento, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

function calcular_estatisticas_equipamentos($equipamentos)
{
    $estatisticas = [
        'total' => count($equipamentos),
        'operacionais' => 0,
        'manutencao' => 0,
        'alta_criticidade' => 0
    ];

    foreach ($equipamentos as $equipamento) {
        if (($equipamento->estado ?? '') === 'Operacional') {
            $estatisticas['operacionais']++;
        }

        if (($equipamento->estado ?? '') === 'Em Manutenção') {
            $estatisticas['manutencao']++;
        }

        if (
            ($equipamento->criticidade ?? '') === 'Alta' ||
            ($equipamento->criticidade ?? '') === 'Suporte de Vida'
        ) {
            $estatisticas['alta_criticidade']++;
        }
    }

    return $estatisticas;
}


// =====================================================
// EQUIPAMENTOS — ABATER / SOFT DELETE
// =====================================================

function abater_equipamento($pdo, $id_equipamento)
{
    if (!validar_id($id_equipamento)) {
        return false;
    }

    $sql = "UPDATE equipamentos
            SET estado = 'Abatido'
            WHERE id_equipamento = :id_equipamento";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_equipamento', $id_equipamento, PDO::PARAM_INT);

    return $stmt->execute();
}


// =====================================================
// EQUIPAMENTOS — CRIAÇÃO
// =====================================================

function criar_localizacao_equipamento($pdo, $edificio, $piso, $servico, $sala)
{
    $sql = "INSERT INTO localizacoes
            (
                edificio,
                piso,
                servico,
                sala,
                tipo_area,
                estado,
                capacidade_equipamentos,
                acesso_restrito,
                prioridade_tecnica,
                observacoes
            )
            VALUES
            (
                :edificio,
                :piso,
                :servico,
                :sala,
                'Outra',
                'Ativa',
                1,
                'Não',
                'Normal',
                'Criada automaticamente no registo de equipamento'
            )";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':edificio', $edificio);
    $stmt->bindValue(':piso', $piso);
    $stmt->bindValue(':servico', $servico);
    $stmt->bindValue(':sala', $sala);
    $stmt->execute();

    return $pdo->lastInsertId();
}

function criar_equipamento($pdo, $dados)
{
    $pdo->beginTransaction();

    try {
        $id_localizacao = criar_localizacao_equipamento(
            $pdo,
            $dados['edificio'],
            $dados['piso'],
            $dados['servico'],
            $dados['sala']
        );

        $sql = "INSERT INTO equipamentos
                (
                    codigo_inventario,
                    designacao,
                    categoria,
                    marca,
                    modelo,
                    num_serie,
                    fabricante,
                    data_aquisicao,
                    ano_fabrico,
                    custo,
                    tipo_entrada,
                    estado,
                    criticidade,
                    id_localizacao,
                    observacoes
                )
                VALUES
                (
                    :codigo_inventario,
                    :designacao,
                    :categoria,
                    :marca,
                    :modelo,
                    :num_serie,
                    :fabricante,
                    :data_aquisicao,
                    :ano_fabrico,
                    :custo,
                    :tipo_entrada,
                    :estado,
                    :criticidade,
                    :id_localizacao,
                    :observacoes
                )";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':codigo_inventario', $dados['codigo_inventario']);
        $stmt->bindValue(':designacao', $dados['designacao']);
        $stmt->bindValue(':categoria', $dados['categoria']);
        $stmt->bindValue(':marca', $dados['marca']);
        $stmt->bindValue(':modelo', $dados['modelo']);
        $stmt->bindValue(':num_serie', $dados['num_serie']);
        $stmt->bindValue(':fabricante', $dados['fabricante']);
        $stmt->bindValue(':data_aquisicao', $dados['data_aquisicao']);
        $stmt->bindValue(':ano_fabrico', $dados['ano_fabrico']);
        $stmt->bindValue(':custo', $dados['custo']);
        $stmt->bindValue(':tipo_entrada', $dados['tipo_entrada']);
        $stmt->bindValue(':estado', $dados['estado']);
        $stmt->bindValue(':criticidade', $dados['criticidade']);
        $stmt->bindValue(':id_localizacao', $id_localizacao);
        $stmt->bindValue(':observacoes', $dados['observacoes']);
        $stmt->execute();

        $id_equipamento = $pdo->lastInsertId();

        $pdo->commit();

        return $id_equipamento;
    } catch (PDOException $erro) {
        $pdo->rollBack();
        throw $erro;
    }
}


// =====================================================
// EQUIPAMENTOS — ATUALIZAÇÃO
// =====================================================

function atualizar_equipamento($pdo, $id_equipamento, $dados, $id_localizacao = null)
{
    $pdo->beginTransaction();

    try {
        $sql = "UPDATE equipamentos
                SET codigo_inventario = :codigo_inventario,
                    designacao = :designacao,
                    categoria = :categoria,
                    marca = :marca,
                    modelo = :modelo,
                    num_serie = :num_serie,
                    fabricante = :fabricante,
                    data_aquisicao = :data_aquisicao,
                    ano_fabrico = :ano_fabrico,
                    custo = :custo,
                    tipo_entrada = :tipo_entrada,
                    estado = :estado,
                    criticidade = :criticidade,
                    observacoes = :observacoes
                WHERE id_equipamento = :id_equipamento";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':codigo_inventario', $dados['codigo_inventario']);
        $stmt->bindValue(':designacao', $dados['designacao']);
        $stmt->bindValue(':categoria', $dados['categoria']);
        $stmt->bindValue(':marca', $dados['marca']);
        $stmt->bindValue(':modelo', $dados['modelo']);
        $stmt->bindValue(':num_serie', $dados['num_serie']);
        $stmt->bindValue(':fabricante', $dados['fabricante']);
        $stmt->bindValue(':data_aquisicao', $dados['data_aquisicao']);
        $stmt->bindValue(':ano_fabrico', $dados['ano_fabrico']);
        $stmt->bindValue(':custo', $dados['custo']);
        $stmt->bindValue(':tipo_entrada', $dados['tipo_entrada']);
        $stmt->bindValue(':estado', $dados['estado']);
        $stmt->bindValue(':criticidade', $dados['criticidade']);
        $stmt->bindValue(':observacoes', $dados['observacoes']);
        $stmt->bindValue(':id_equipamento', $id_equipamento, PDO::PARAM_INT);
        $stmt->execute();

        if (!empty($id_localizacao)) {
            $sqlLocalizacao = "UPDATE localizacoes
                               SET edificio = :edificio,
                                   piso = :piso,
                                   servico = :servico,
                                   sala = :sala
                               WHERE id_localizacao = :id_localizacao";

            $stmtLocalizacao = $pdo->prepare($sqlLocalizacao);
            $stmtLocalizacao->bindValue(':edificio', $dados['edificio']);
            $stmtLocalizacao->bindValue(':piso', $dados['piso']);
            $stmtLocalizacao->bindValue(':servico', $dados['servico']);
            $stmtLocalizacao->bindValue(':sala', $dados['sala']);
            $stmtLocalizacao->bindValue(':id_localizacao', $id_localizacao, PDO::PARAM_INT);
            $stmtLocalizacao->execute();
        }

        $pdo->commit();

        return true;
    } catch (PDOException $erro) {
        $pdo->rollBack();
        throw $erro;
    }
}


// =====================================================
// EQUIPAMENTOS — FORMULÁRIOS E VALIDAÇÃO
// =====================================================

function recolher_dados_equipamento_post()
{
    return [
        'codigo_inventario' => limpar_texto($_POST['codigo_inventario'] ?? ''),
        'designacao' => limpar_texto($_POST['designacao'] ?? ''),
        'categoria' => limpar_texto($_POST['categoria'] ?? ''),
        'marca' => limpar_texto($_POST['marca'] ?? ''),
        'modelo' => limpar_texto($_POST['modelo'] ?? ''),
        'num_serie' => limpar_texto($_POST['num_serie'] ?? ''),
        'fabricante' => limpar_texto($_POST['fabricante'] ?? ''),

        'data_aquisicao' => $_POST['data_aquisicao'] ?? '',
        'ano_fabrico' => $_POST['ano_fabrico'] ?? '',
        'custo' => $_POST['custo'] ?? '',
        'tipo_entrada' => limpar_texto($_POST['tipo_entrada'] ?? ''),
        'estado' => limpar_texto($_POST['estado'] ?? ''),
        'criticidade' => limpar_texto($_POST['criticidade'] ?? ''),

        'edificio' => limpar_texto($_POST['edificio'] ?? ''),
        'piso' => $_POST['piso'] ?? '',
        'servico' => limpar_texto($_POST['servico'] ?? ''),
        'sala' => limpar_texto($_POST['sala'] ?? ''),

        'observacoes' => limpar_texto($_POST['observacoes'] ?? '')
    ];
}

function validar_dados_equipamento($dados, $validar_localizacao = true)
{
    $campos_obrigatorios = [
        'codigo_inventario',
        'designacao',
        'categoria',
        'marca',
        'modelo',
        'num_serie',
        'fabricante',
        'data_aquisicao',
        'ano_fabrico',
        'custo',
        'tipo_entrada',
        'estado',
        'criticidade'
    ];

    if ($validar_localizacao) {
        $campos_obrigatorios[] = 'edificio';
        $campos_obrigatorios[] = 'piso';
        $campos_obrigatorios[] = 'servico';
        $campos_obrigatorios[] = 'sala';
    }

    foreach ($campos_obrigatorios as $campo) {
        if (empty($dados[$campo])) {
            return false;
        }
    }

    return true;
}