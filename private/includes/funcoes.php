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

function valor_ou_null($valor)
{
    $valor = limpar_texto($valor);

    return $valor !== '' ? $valor : null;
}

function bind_valor_ou_null($stmt, $parametro, $valor)
{
    if ($valor === null || $valor === '') {
        $stmt->bindValue($parametro, null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue($parametro, $valor);
    }
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

// =====================================================
// FORNECEDORES — BADGES / ESTADOS VISUAIS
// =====================================================

function badge_estado_fornecedor($estado)
{
    return match ($estado) {
        'Ativo' => 'success',
        'Inativo' => 'secondary',
        default => 'secondary'
    };
}

function badge_prioridade_fornecedor($prioridade)
{
    return match ($prioridade) {
        'Normal' => 'secondary',
        'Alta' => 'warning text-dark',
        'Urgente' => 'danger',
        default => 'secondary'
    };
}

function badge_tipo_fornecedor($tipo)
{
    return match ($tipo) {
        'Fabricante' => 'success',
        'Distribuidor / Fornecedor Comercial' => 'info text-dark',
        'Empresa de Assistência Técnica' => 'warning text-dark',
        'Fornecedor de Consumíveis/Acessórios' => 'secondary',
        default => 'primary'
    };
}

function fornecedor_inativo($fornecedor)
{
    return ($fornecedor->estado ?? '') === 'Inativo';
}


// =====================================================
// FORNECEDORES — LISTAGEM E CONSULTA
// =====================================================

function listar_fornecedores($pdo)
{
    $sql = "SELECT 
                f.*,
                COALESCE((
                    SELECT COUNT(*)
                    FROM equipamentos e
                    WHERE e.id_fornecedor_principal = f.id_fornecedor
                ), 0) AS total_equipamentos_associados
            FROM fornecedores f
            ORDER BY f.id_fornecedor DESC";

    $stmt = $pdo->query($sql);

    return $stmt->fetchAll();
}

function buscar_fornecedor_por_id($pdo, $id_fornecedor)
{
    if (!validar_id($id_fornecedor)) {
        return false;
    }

    $sql = "SELECT 
                f.*,
                COALESCE((
                    SELECT COUNT(*)
                    FROM equipamentos e
                    WHERE e.id_fornecedor_principal = f.id_fornecedor
                ), 0) AS total_equipamentos_associados
            FROM fornecedores f
            WHERE f.id_fornecedor = :id_fornecedor
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_fornecedor', $id_fornecedor, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch();
}

function calcular_estatisticas_fornecedores($fornecedores)
{
    $estatisticas = [
        'total' => count($fornecedores),
        'fabricantes' => 0,
        'assistencia' => 0,
        'equipamentos_associados' => 0
    ];

    foreach ($fornecedores as $fornecedor) {
        $tipo = $fornecedor->tipo_fornecedor ?? '';

        if ($tipo === 'Fabricante') {
            $estatisticas['fabricantes']++;
        }

        if ($tipo === 'Empresa de Assistência Técnica') {
            $estatisticas['assistencia']++;
        }

        $estatisticas['equipamentos_associados'] += (int) ($fornecedor->total_equipamentos_associados ?? 0);
    }

    return $estatisticas;
}


// =====================================================
// FORNECEDORES — CRIAÇÃO
// =====================================================

function criar_fornecedor($pdo, $dados)
{
    $sql = "INSERT INTO fornecedores
            (
                nome_empresa,
                nif,
                estado,
                tipo_fornecedor,
                area_atuacao,
                email,
                telefone,
                website,
                pessoa_contacto,
                tel_pessoa,
                morada,
                contrato_ativo,
                relacao_hospital,
                prioridade_contacto,
                observacoes
            )
            VALUES
            (
                :nome_empresa,
                :nif,
                :estado,
                :tipo_fornecedor,
                :area_atuacao,
                :email,
                :telefone,
                :website,
                :pessoa_contacto,
                :tel_pessoa,
                :morada,
                :contrato_ativo,
                :relacao_hospital,
                :prioridade_contacto,
                :observacoes
            )";

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':nome_empresa', $dados['nome_empresa']);
    $stmt->bindValue(':nif', $dados['nif']);
    $stmt->bindValue(':estado', $dados['estado']);
    $stmt->bindValue(':tipo_fornecedor', $dados['tipo_fornecedor']);
    $stmt->bindValue(':area_atuacao', $dados['area_atuacao']);
    $stmt->bindValue(':email', $dados['email']);

    bind_valor_ou_null($stmt, ':telefone', $dados['telefone']);
    bind_valor_ou_null($stmt, ':website', $dados['website']);
    bind_valor_ou_null($stmt, ':pessoa_contacto', $dados['pessoa_contacto']);
    bind_valor_ou_null($stmt, ':tel_pessoa', $dados['tel_pessoa']);
    bind_valor_ou_null($stmt, ':morada', $dados['morada']);

    $stmt->bindValue(':contrato_ativo', $dados['contrato_ativo']);
    bind_valor_ou_null($stmt, ':relacao_hospital', $dados['relacao_hospital']);
    $stmt->bindValue(':prioridade_contacto', $dados['prioridade_contacto']);
    bind_valor_ou_null($stmt, ':observacoes', $dados['observacoes']);

    $stmt->execute();

    return $pdo->lastInsertId();
}


// =====================================================
// FORNECEDORES — ATUALIZAÇÃO
// =====================================================

function atualizar_fornecedor($pdo, $id_fornecedor, $dados)
{
    if (!validar_id($id_fornecedor)) {
        return false;
    }

    $sql = "UPDATE fornecedores
            SET nome_empresa = :nome_empresa,
                nif = :nif,
                estado = :estado,
                tipo_fornecedor = :tipo_fornecedor,
                area_atuacao = :area_atuacao,
                email = :email,
                telefone = :telefone,
                website = :website,
                pessoa_contacto = :pessoa_contacto,
                tel_pessoa = :tel_pessoa,
                morada = :morada,
                contrato_ativo = :contrato_ativo,
                relacao_hospital = :relacao_hospital,
                prioridade_contacto = :prioridade_contacto,
                observacoes = :observacoes
            WHERE id_fornecedor = :id_fornecedor";

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':nome_empresa', $dados['nome_empresa']);
    $stmt->bindValue(':nif', $dados['nif']);
    $stmt->bindValue(':estado', $dados['estado']);
    $stmt->bindValue(':tipo_fornecedor', $dados['tipo_fornecedor']);
    $stmt->bindValue(':area_atuacao', $dados['area_atuacao']);
    $stmt->bindValue(':email', $dados['email']);

    bind_valor_ou_null($stmt, ':telefone', $dados['telefone']);
    bind_valor_ou_null($stmt, ':website', $dados['website']);
    bind_valor_ou_null($stmt, ':pessoa_contacto', $dados['pessoa_contacto']);
    bind_valor_ou_null($stmt, ':tel_pessoa', $dados['tel_pessoa']);
    bind_valor_ou_null($stmt, ':morada', $dados['morada']);

    $stmt->bindValue(':contrato_ativo', $dados['contrato_ativo']);
    bind_valor_ou_null($stmt, ':relacao_hospital', $dados['relacao_hospital']);
    $stmt->bindValue(':prioridade_contacto', $dados['prioridade_contacto']);
    bind_valor_ou_null($stmt, ':observacoes', $dados['observacoes']);

    $stmt->bindValue(':id_fornecedor', $id_fornecedor, PDO::PARAM_INT);

    return $stmt->execute();
}


// =====================================================
// FORNECEDORES — INATIVAR / SOFT DELETE
// =====================================================

function inativar_fornecedor($pdo, $id_fornecedor)
{
    if (!validar_id($id_fornecedor)) {
        return false;
    }

    $sql = "UPDATE fornecedores
            SET estado = 'Inativo'
            WHERE id_fornecedor = :id_fornecedor";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_fornecedor', $id_fornecedor, PDO::PARAM_INT);

    return $stmt->execute();
}


// =====================================================
// FORNECEDORES — ASSOCIAÇÃO A EQUIPAMENTOS
// =====================================================

function listar_equipamentos_para_associar_fornecedor($pdo)
{
    $sql = "SELECT 
                id_equipamento,
                codigo_inventario,
                designacao,
                marca,
                modelo,
                estado,
                id_fornecedor_principal
            FROM equipamentos
            WHERE estado <> 'Abatido'
            ORDER BY designacao ASC";

    $stmt = $pdo->query($sql);

    return $stmt->fetchAll();
}

function associar_fornecedor_equipamento($pdo, $id_fornecedor, $id_equipamento)
{
    if (!validar_id($id_fornecedor) || !validar_id($id_equipamento)) {
        return false;
    }

    $sql = "UPDATE equipamentos
            SET id_fornecedor_principal = :id_fornecedor
            WHERE id_equipamento = :id_equipamento";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_fornecedor', $id_fornecedor, PDO::PARAM_INT);
    $stmt->bindValue(':id_equipamento', $id_equipamento, PDO::PARAM_INT);

    return $stmt->execute();
}


// =====================================================
// FORNECEDORES — FORMULÁRIOS E VALIDAÇÃO
// =====================================================

function recolher_dados_fornecedor_post()
{
    return [
        'nome_empresa' => limpar_texto($_POST['nome_empresa'] ?? ''),
        'nif' => limpar_texto($_POST['nif'] ?? ''),
        'estado' => limpar_texto($_POST['estado'] ?? 'Ativo'),
        'tipo_fornecedor' => limpar_texto($_POST['tipo_fornecedor'] ?? ''),
        'area_atuacao' => limpar_texto($_POST['area_atuacao'] ?? 'Outro'),
        'email' => limpar_texto($_POST['email'] ?? ''),

        'telefone' => valor_ou_null($_POST['telefone'] ?? ''),
        'website' => valor_ou_null($_POST['website'] ?? ''),
        'pessoa_contacto' => valor_ou_null($_POST['pessoa_contacto'] ?? ''),
        'tel_pessoa' => valor_ou_null($_POST['tel_pessoa'] ?? ''),
        'morada' => valor_ou_null($_POST['morada'] ?? ''),

        'contrato_ativo' => limpar_texto($_POST['contrato_ativo'] ?? 'Não'),
        'relacao_hospital' => valor_ou_null($_POST['relacao_hospital'] ?? ''),
        'prioridade_contacto' => limpar_texto($_POST['prioridade_contacto'] ?? 'Normal'),
        'observacoes' => valor_ou_null($_POST['observacoes'] ?? '')
    ];
}

function validar_dados_fornecedor($dados)
{
    $campos_obrigatorios = [
        'nome_empresa',
        'nif',
        'estado',
        'tipo_fornecedor',
        'area_atuacao',
        'email'
    ];

    foreach ($campos_obrigatorios as $campo) {
        if (empty($dados[$campo])) {
            return false;
        }
    }

    if (!preg_match('/^[0-9]{9}$/', $dados['nif'])) {
        return false;
    }

    if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    if (!empty($dados['telefone']) && !preg_match('/^[0-9]{9}$/', $dados['telefone'])) {
        return false;
    }

    if (!empty($dados['tel_pessoa']) && !preg_match('/^[0-9]{9}$/', $dados['tel_pessoa'])) {
        return false;
    }

    $tipos_validos = [
        'Fabricante',
        'Distribuidor / Fornecedor Comercial',
        'Empresa de Assistência Técnica',
        'Fornecedor de Consumíveis/Acessórios'
    ];

    if (!in_array($dados['tipo_fornecedor'], $tipos_validos, true)) {
        return false;
    }

    $areas_validas = [
        'Diagnóstico e Imagiologia',
        'Monitorização',
        'Suporte de Vida',
        'Laboratório',
        'Consumíveis Hospitalares',
        'Assistência Técnica',
        'Outro'
    ];

    if (!in_array($dados['area_atuacao'], $areas_validas, true)) {
        return false;
    }

    $estados_validos = ['Ativo', 'Inativo'];

    if (!in_array($dados['estado'], $estados_validos, true)) {
        return false;
    }

    $contrato_validos = ['Sim', 'Não'];

    if (!in_array($dados['contrato_ativo'], $contrato_validos, true)) {
        return false;
    }

    $prioridades_validas = ['Normal', 'Alta', 'Urgente'];

    if (!in_array($dados['prioridade_contacto'], $prioridades_validas, true)) {
        return false;
    }

    $relacoes_validas = [
        null,
        '',
        'Fornecedor principal',
        'Fornecedor secundário',
        'Prestador de assistência técnica',
        'Fornecedor de consumíveis',
        'Fabricante sem contrato direto'
    ];

    if (!in_array($dados['relacao_hospital'], $relacoes_validas, true)) {
        return false;
    }

    return true;
}
// =====================================================
// LOCALIZAÇÕES — BADGES / ESTADOS VISUAIS
// =====================================================

function badge_estado_localizacao($estado)
{
    return match ($estado) {
        'Ativa' => 'success',
        'Inativa' => 'secondary',
        'Em obras' => 'warning text-dark',
        'Indisponível' => 'danger',
        default => 'secondary'
    };
}

function badge_tipo_area_localizacao($tipo_area)
{
    return match ($tipo_area) {
        'Área Crítica' => 'danger',
        'Diagnóstico' => 'primary',
        'Internamento' => 'success',
        'Laboratório' => 'info text-dark',
        'Armazém' => 'secondary',
        'Administrativa' => 'dark',
        'Outra' => 'secondary',
        default => 'secondary'
    };
}

function badge_prioridade_localizacao($prioridade)
{
    return match ($prioridade) {
        'Normal' => 'secondary',
        'Alta' => 'warning text-dark',
        'Crítica' => 'danger',
        default => 'secondary'
    };
}

function localizacao_inativa($localizacao)
{
    return ($localizacao->estado ?? '') === 'Inativa';
}


// =====================================================
// LOCALIZAÇÕES — LISTAGEM E CONSULTA
// =====================================================

function listar_localizacoes($pdo)
{
    $sql = "SELECT 
                l.*,
                COALESCE((
                    SELECT COUNT(*)
                    FROM equipamentos e
                    WHERE e.id_localizacao = l.id_localizacao
                    AND e.estado <> 'Abatido'
                ), 0) AS total_equipamentos_associados
            FROM localizacoes l
            ORDER BY l.id_localizacao DESC";

    $stmt = $pdo->query($sql);

    return $stmt->fetchAll();
}

function buscar_localizacao_por_id($pdo, $id_localizacao)
{
    if (!validar_id($id_localizacao)) {
        return false;
    }

    $sql = "SELECT 
                l.*,
                COALESCE((
                    SELECT COUNT(*)
                    FROM equipamentos e
                    WHERE e.id_localizacao = l.id_localizacao
                    AND e.estado <> 'Abatido'
                ), 0) AS total_equipamentos_associados
            FROM localizacoes l
            WHERE l.id_localizacao = :id_localizacao
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_localizacao', $id_localizacao, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch();
}

function buscar_equipamentos_da_localizacao($pdo, $id_localizacao)
{
    if (!validar_id($id_localizacao)) {
        return [];
    }

    $sql = "SELECT 
                id_equipamento,
                codigo_inventario,
                designacao,
                marca,
                modelo,
                estado,
                criticidade
            FROM equipamentos
            WHERE id_localizacao = :id_localizacao
            AND estado <> 'Abatido'
            ORDER BY designacao ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_localizacao', $id_localizacao, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

function calcular_estatisticas_localizacoes($localizacoes)
{
    $estatisticas = [
        'total' => count($localizacoes),
        'ativas' => 0,
        'inativas' => 0,
        'equipamentos_associados' => 0
    ];

    foreach ($localizacoes as $localizacao) {
        if (($localizacao->estado ?? '') === 'Ativa') {
            $estatisticas['ativas']++;
        }

        if (($localizacao->estado ?? '') === 'Inativa') {
            $estatisticas['inativas']++;
        }

        $estatisticas['equipamentos_associados'] += (int) ($localizacao->total_equipamentos_associados ?? 0);
    }

    return $estatisticas;
}


// =====================================================
// LOCALIZAÇÕES — CRIAÇÃO
// =====================================================

function criar_localizacao($pdo, $dados)
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
                :tipo_area,
                :estado,
                :capacidade_equipamentos,
                :acesso_restrito,
                :prioridade_tecnica,
                :observacoes
            )";

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':edificio', $dados['edificio']);
    $stmt->bindValue(':piso', $dados['piso'], PDO::PARAM_INT);
    $stmt->bindValue(':servico', $dados['servico']);
    $stmt->bindValue(':sala', $dados['sala']);
    $stmt->bindValue(':tipo_area', $dados['tipo_area']);
    $stmt->bindValue(':estado', $dados['estado']);
    $stmt->bindValue(':capacidade_equipamentos', $dados['capacidade_equipamentos'], PDO::PARAM_INT);
    $stmt->bindValue(':acesso_restrito', $dados['acesso_restrito']);
    $stmt->bindValue(':prioridade_tecnica', $dados['prioridade_tecnica']);
    bind_valor_ou_null($stmt, ':observacoes', $dados['observacoes']);

    $stmt->execute();

    return $pdo->lastInsertId();
}


// =====================================================
// LOCALIZAÇÕES — ATUALIZAÇÃO
// =====================================================

function atualizar_localizacao($pdo, $id_localizacao, $dados)
{
    if (!validar_id($id_localizacao)) {
        return false;
    }

    $sql = "UPDATE localizacoes
            SET edificio = :edificio,
                piso = :piso,
                servico = :servico,
                sala = :sala,
                tipo_area = :tipo_area,
                estado = :estado,
                capacidade_equipamentos = :capacidade_equipamentos,
                acesso_restrito = :acesso_restrito,
                prioridade_tecnica = :prioridade_tecnica,
                observacoes = :observacoes
            WHERE id_localizacao = :id_localizacao";

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':edificio', $dados['edificio']);
    $stmt->bindValue(':piso', $dados['piso'], PDO::PARAM_INT);
    $stmt->bindValue(':servico', $dados['servico']);
    $stmt->bindValue(':sala', $dados['sala']);
    $stmt->bindValue(':tipo_area', $dados['tipo_area']);
    $stmt->bindValue(':estado', $dados['estado']);
    $stmt->bindValue(':capacidade_equipamentos', $dados['capacidade_equipamentos'], PDO::PARAM_INT);
    $stmt->bindValue(':acesso_restrito', $dados['acesso_restrito']);
    $stmt->bindValue(':prioridade_tecnica', $dados['prioridade_tecnica']);
    bind_valor_ou_null($stmt, ':observacoes', $dados['observacoes']);

    $stmt->bindValue(':id_localizacao', $id_localizacao, PDO::PARAM_INT);

    return $stmt->execute();
}


// =====================================================
// LOCALIZAÇÕES — INATIVAR / SOFT DELETE
// =====================================================

function inativar_localizacao($pdo, $id_localizacao)
{
    if (!validar_id($id_localizacao)) {
        return false;
    }

    $sql = "UPDATE localizacoes
            SET estado = 'Inativa'
            WHERE id_localizacao = :id_localizacao";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_localizacao', $id_localizacao, PDO::PARAM_INT);

    return $stmt->execute();
}


// =====================================================
// LOCALIZAÇÕES — FORMULÁRIOS E VALIDAÇÃO
// =====================================================

function recolher_dados_localizacao_post()
{
    $capacidade = $_POST['capacidade_equipamentos'] ?? '';

    return [
        'edificio' => limpar_texto($_POST['edificio'] ?? ''),
        'piso' => $_POST['piso'] ?? '',
        'servico' => limpar_texto($_POST['servico'] ?? ''),
        'sala' => limpar_texto($_POST['sala'] ?? ''),
        'tipo_area' => limpar_texto($_POST['tipo_area'] ?? 'Outra'),
        'estado' => limpar_texto($_POST['estado'] ?? 'Ativa'),
        'capacidade_equipamentos' => $capacidade !== '' ? (int) $capacidade : 0,
        'acesso_restrito' => limpar_texto($_POST['acesso_restrito'] ?? 'Não'),
        'prioridade_tecnica' => limpar_texto($_POST['prioridade_tecnica'] ?? 'Normal'),
        'observacoes' => valor_ou_null($_POST['observacoes'] ?? '')
    ];
}

function validar_dados_localizacao($dados)
{
    $campos_obrigatorios = [
        'edificio',
        'piso',
        'servico',
        'sala',
        'tipo_area',
        'estado'
    ];

    foreach ($campos_obrigatorios as $campo) {
        if (!isset($dados[$campo]) || $dados[$campo] === '') {
            return false;
        }
    }

    if (!is_numeric($dados['piso']) || (int) $dados['piso'] < 0 || (int) $dados['piso'] > 20) {
        return false;
    }

    if (
        !is_numeric($dados['capacidade_equipamentos']) ||
        (int) $dados['capacidade_equipamentos'] < 0 ||
        (int) $dados['capacidade_equipamentos'] > 200
    ) {
        return false;
    }

    $tipos_validos = [
        'Área Crítica',
        'Diagnóstico',
        'Internamento',
        'Laboratório',
        'Armazém',
        'Administrativa',
        'Outra'
    ];

    if (!in_array($dados['tipo_area'], $tipos_validos, true)) {
        return false;
    }

    $estados_validos = [
        'Ativa',
        'Inativa',
        'Em obras',
        'Indisponível'
    ];

    if (!in_array($dados['estado'], $estados_validos, true)) {
        return false;
    }

    $acesso_validos = [
        'Sim',
        'Não'
    ];

    if (!in_array($dados['acesso_restrito'], $acesso_validos, true)) {
        return false;
    }

    $prioridades_validas = [
        'Normal',
        'Alta',
        'Crítica'
    ];

    if (!in_array($dados['prioridade_tecnica'], $prioridades_validas, true)) {
        return false;
    }

    return true;
}

// =====================================================
// GARANTIAS E CONTRATOS — BADGES / ESTADOS VISUAIS
// =====================================================

function badge_estado_garantia($estado)
{
    return match ($estado) {
        'Ativa' => 'success',
        'A Terminar' => 'warning text-dark',
        'Expirada' => 'danger',
        default => 'secondary'
    };
}

function badge_tipo_contrato($tipo_contrato)
{
    return match ($tipo_contrato) {
        'Garantia' => 'primary',
        'Preventiva' => 'info text-dark',
        'Corretiva' => 'secondary',
        'Full Service' => 'dark',
        default => 'secondary'
    };
}

function badge_criticidade_garantia($criticidade)
{
    return match ($criticidade) {
        'Baixa' => 'secondary',
        'Média' => 'info text-dark',
        'Alta' => 'warning text-dark',
        'Suporte de Vida' => 'danger',
        default => 'secondary'
    };
}

function garantia_expirada($garantia)
{
    return ($garantia->estado ?? '') === 'Expirada';
}


// =====================================================
// GARANTIAS E CONTRATOS — LISTAGEM E CONSULTA
// =====================================================

function listar_garantias($pdo)
{
    $sql = "SELECT 
                g.*,

                e.codigo_inventario,
                e.designacao AS equipamento_designacao,
                e.marca AS equipamento_marca,
                e.modelo AS equipamento_modelo,

                f.nome_empresa AS fornecedor_nome,
                f.nif AS fornecedor_nif,
                f.email AS fornecedor_email,
                f.telefone AS fornecedor_telefone,
                f.pessoa_contacto AS fornecedor_pessoa_contacto,
                f.prioridade_contacto AS fornecedor_prioridade

            FROM garantias_contratos g
            INNER JOIN equipamentos e ON g.id_equipamento = e.id_equipamento
            LEFT JOIN fornecedores f ON g.id_fornecedor = f.id_fornecedor
            ORDER BY g.data_fim ASC, g.id_garantia DESC";

    $stmt = $pdo->query($sql);

    return $stmt->fetchAll();
}

function buscar_garantia_por_id($pdo, $id_garantia)
{
    if (!validar_id($id_garantia)) {
        return false;
    }

    $sql = "SELECT 
                g.*,

                e.codigo_inventario,
                e.designacao AS equipamento_designacao,
                e.marca AS equipamento_marca,
                e.modelo AS equipamento_modelo,
                e.num_serie AS equipamento_num_serie,
                e.estado AS equipamento_estado,

                f.nome_empresa AS fornecedor_nome,
                f.nif AS fornecedor_nif,
                f.email AS fornecedor_email,
                f.telefone AS fornecedor_telefone,
                f.pessoa_contacto AS fornecedor_pessoa_contacto,
                f.prioridade_contacto AS fornecedor_prioridade

            FROM garantias_contratos g
            INNER JOIN equipamentos e ON g.id_equipamento = e.id_equipamento
            LEFT JOIN fornecedores f ON g.id_fornecedor = f.id_fornecedor
            WHERE g.id_garantia = :id_garantia
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_garantia', $id_garantia, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch();
}

function calcular_estatisticas_garantias($garantias)
{
    $estatisticas = [
        'total' => count($garantias),
        'ativas' => 0,
        'a_terminar' => 0,
        'expiradas' => 0
    ];

    foreach ($garantias as $garantia) {
        $estado = $garantia->estado ?? '';

        if ($estado === 'Ativa') {
            $estatisticas['ativas']++;
        }

        if ($estado === 'A Terminar') {
            $estatisticas['a_terminar']++;
        }

        if ($estado === 'Expirada') {
            $estatisticas['expiradas']++;
        }
    }

    return $estatisticas;
}


// =====================================================
// GARANTIAS E CONTRATOS — DADOS PARA SELECTS
// =====================================================

function listar_equipamentos_para_garantia($pdo)
{
    $sql = "SELECT 
                id_equipamento,
                codigo_inventario,
                designacao,
                marca,
                modelo,
                estado
            FROM equipamentos
            WHERE estado <> 'Abatido'
            ORDER BY codigo_inventario ASC";

    $stmt = $pdo->query($sql);

    return $stmt->fetchAll();
}

function listar_fornecedores_para_garantia($pdo)
{
    $sql = "SELECT 
                id_fornecedor,
                nome_empresa,
                tipo_fornecedor,
                estado
            FROM fornecedores
            WHERE estado = 'Ativo'
            ORDER BY nome_empresa ASC";

    $stmt = $pdo->query($sql);

    return $stmt->fetchAll();
}


// =====================================================
// GARANTIAS E CONTRATOS — CRIAÇÃO
// =====================================================

function criar_garantia($pdo, $dados)
{
    $sql = "INSERT INTO garantias_contratos
            (
                id_equipamento,
                id_fornecedor,
                tipo_contrato,
                estado,
                criticidade,
                numero_contrato,
                data_inicio,
                data_fim,
                custo_anual,
                pessoa_contacto,
                telefone_contacto,
                documento_associado,
                caminho_documento,
                observacoes
            )
            VALUES
            (
                :id_equipamento,
                :id_fornecedor,
                :tipo_contrato,
                :estado,
                :criticidade,
                :numero_contrato,
                :data_inicio,
                :data_fim,
                :custo_anual,
                :pessoa_contacto,
                :telefone_contacto,
                :documento_associado,
                :caminho_documento,
                :observacoes
            )";

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':id_equipamento', $dados['id_equipamento'], PDO::PARAM_INT);
    bind_valor_ou_null($stmt, ':id_fornecedor', $dados['id_fornecedor']);

    $stmt->bindValue(':tipo_contrato', $dados['tipo_contrato']);
    $stmt->bindValue(':estado', $dados['estado']);
    $stmt->bindValue(':criticidade', $dados['criticidade']);

    bind_valor_ou_null($stmt, ':numero_contrato', $dados['numero_contrato']);
    $stmt->bindValue(':data_inicio', $dados['data_inicio']);
    $stmt->bindValue(':data_fim', $dados['data_fim']);
    $stmt->bindValue(':custo_anual', $dados['custo_anual']);

    bind_valor_ou_null($stmt, ':pessoa_contacto', $dados['pessoa_contacto']);
    bind_valor_ou_null($stmt, ':telefone_contacto', $dados['telefone_contacto']);
    bind_valor_ou_null($stmt, ':documento_associado', $dados['documento_associado']);
    bind_valor_ou_null($stmt, ':caminho_documento', $dados['caminho_documento']);
    bind_valor_ou_null($stmt, ':observacoes', $dados['observacoes']);

    $stmt->execute();

    return $pdo->lastInsertId();
}


// =====================================================
// GARANTIAS E CONTRATOS — ATUALIZAÇÃO
// =====================================================

function atualizar_garantia($pdo, $id_garantia, $dados)
{
    if (!validar_id($id_garantia)) {
        return false;
    }

    $sql = "UPDATE garantias_contratos
            SET id_equipamento = :id_equipamento,
                id_fornecedor = :id_fornecedor,
                tipo_contrato = :tipo_contrato,
                estado = :estado,
                criticidade = :criticidade,
                numero_contrato = :numero_contrato,
                data_inicio = :data_inicio,
                data_fim = :data_fim,
                custo_anual = :custo_anual,
                pessoa_contacto = :pessoa_contacto,
                telefone_contacto = :telefone_contacto,
                documento_associado = :documento_associado,
                caminho_documento = :caminho_documento,
                observacoes = :observacoes
            WHERE id_garantia = :id_garantia";

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':id_equipamento', $dados['id_equipamento'], PDO::PARAM_INT);
    bind_valor_ou_null($stmt, ':id_fornecedor', $dados['id_fornecedor']);

    $stmt->bindValue(':tipo_contrato', $dados['tipo_contrato']);
    $stmt->bindValue(':estado', $dados['estado']);
    $stmt->bindValue(':criticidade', $dados['criticidade']);

    bind_valor_ou_null($stmt, ':numero_contrato', $dados['numero_contrato']);
    $stmt->bindValue(':data_inicio', $dados['data_inicio']);
    $stmt->bindValue(':data_fim', $dados['data_fim']);
    $stmt->bindValue(':custo_anual', $dados['custo_anual']);

    bind_valor_ou_null($stmt, ':pessoa_contacto', $dados['pessoa_contacto']);
    bind_valor_ou_null($stmt, ':telefone_contacto', $dados['telefone_contacto']);
    bind_valor_ou_null($stmt, ':documento_associado', $dados['documento_associado']);
    bind_valor_ou_null($stmt, ':caminho_documento', $dados['caminho_documento']);
    bind_valor_ou_null($stmt, ':observacoes', $dados['observacoes']);

    $stmt->bindValue(':id_garantia', $id_garantia, PDO::PARAM_INT);

    return $stmt->execute();
}


// =====================================================
// GARANTIAS E CONTRATOS — TERMINAR / SOFT DELETE
// =====================================================

function terminar_garantia($pdo, $id_garantia)
{
    if (!validar_id($id_garantia)) {
        return false;
    }

    $sql = "UPDATE garantias_contratos
            SET estado = 'Expirada'
            WHERE id_garantia = :id_garantia";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_garantia', $id_garantia, PDO::PARAM_INT);

    return $stmt->execute();
}


// =====================================================
// GARANTIAS E CONTRATOS — FORMULÁRIOS E VALIDAÇÃO
// =====================================================

function recolher_dados_garantia_post()
{
    $id_fornecedor = $_POST['id_fornecedor'] ?? '';
    $custo_anual = $_POST['custo_anual'] ?? '';

    return [
        'id_equipamento' => $_POST['id_equipamento'] ?? '',
        'id_fornecedor' => $id_fornecedor !== '' ? $id_fornecedor : null,

        'tipo_contrato' => limpar_texto($_POST['tipo_contrato'] ?? ''),
        'estado' => limpar_texto($_POST['estado'] ?? 'Ativa'),
        'criticidade' => limpar_texto($_POST['criticidade'] ?? ''),

        'numero_contrato' => valor_ou_null($_POST['numero_contrato'] ?? ''),
        'data_inicio' => $_POST['data_inicio'] ?? '',
        'data_fim' => $_POST['data_fim'] ?? '',
        'custo_anual' => $custo_anual !== '' ? $custo_anual : 0,

        'pessoa_contacto' => valor_ou_null($_POST['pessoa_contacto'] ?? ''),
        'telefone_contacto' => valor_ou_null($_POST['telefone_contacto'] ?? ''),
        'documento_associado' => valor_ou_null($_POST['documento_associado'] ?? ''),
        'caminho_documento' => valor_ou_null($_POST['caminho_documento'] ?? ''),
        'observacoes' => valor_ou_null($_POST['observacoes'] ?? '')
    ];
}

function validar_dados_garantia($dados)
{
    $campos_obrigatorios = [
        'id_equipamento',
        'tipo_contrato',
        'estado',
        'criticidade',
        'data_inicio',
        'data_fim'
    ];

    foreach ($campos_obrigatorios as $campo) {
        if (!isset($dados[$campo]) || $dados[$campo] === '') {
            return false;
        }
    }

    if (!validar_id($dados['id_equipamento'])) {
        return false;
    }

    if (!empty($dados['id_fornecedor']) && !validar_id($dados['id_fornecedor'])) {
        return false;
    }

    $tipos_validos = [
        'Garantia',
        'Preventiva',
        'Corretiva',
        'Full Service'
    ];

    if (!in_array($dados['tipo_contrato'], $tipos_validos, true)) {
        return false;
    }

    $estados_validos = [
        'Ativa',
        'A Terminar',
        'Expirada'
    ];

    if (!in_array($dados['estado'], $estados_validos, true)) {
        return false;
    }

    $criticidades_validas = [
        'Baixa',
        'Média',
        'Alta',
        'Suporte de Vida'
    ];

    if (!in_array($dados['criticidade'], $criticidades_validas, true)) {
        return false;
    }

    if (strtotime($dados['data_fim']) < strtotime($dados['data_inicio'])) {
        return false;
    }

    if (!is_numeric($dados['custo_anual']) || (float) $dados['custo_anual'] < 0) {
        return false;
    }

    if (!empty($dados['telefone_contacto']) && !preg_match('/^[0-9]{9}$/', $dados['telefone_contacto'])) {
        return false;
    }

    return true;
}