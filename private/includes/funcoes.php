
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

function fornecedor_inativo($fornecedor)
{
    return ($fornecedor->estado ?? '') === 'Inativo';
}


// =====================================================
// FORNECEDORES — LISTAGEM E CONSULTA
// =====================================================

function listar_fornecedores($pdo)
{
    try {
        $sql = "SELECT * FROM vw_fornecedores_completo ORDER BY id_fornecedor DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $erro) {
        $sql = "SELECT * FROM fornecedores ORDER BY id_fornecedor DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }
}

function buscar_fornecedor_por_id($pdo, $id_fornecedor)
{
    if (!validar_id($id_fornecedor)) {
        return false;
    }

    try {
        $sql = "SELECT * FROM vw_fornecedores_completo
                WHERE id_fornecedor = :id_fornecedor
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id_fornecedor', $id_fornecedor, PDO::PARAM_INT);
        $stmt->execute();

        $fornecedor = $stmt->fetch();

        if ($fornecedor) {
            return $fornecedor;
        }
    } catch (PDOException $erro) {
        // Se a view falhar, procura na tabela base.
    }

    $sql = "SELECT * FROM fornecedores
            WHERE id_fornecedor = :id_fornecedor
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_fornecedor', $id_fornecedor, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch();
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

    return true;
}