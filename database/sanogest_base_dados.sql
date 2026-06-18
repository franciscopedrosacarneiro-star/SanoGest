-- =====================================================
-- BASE DE DADOS SanoGest
-- Gerada a partir do projeto HTML enviado em SanoGest.zip
-- Compatível com MySQL/MariaDB no Laragon/phpMyAdmin
-- =====================================================

DROP DATABASE IF EXISTS sanogest;
CREATE DATABASE sanogest
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE sanogest;

SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- 1. UTILIZADORES / LOGIN
-- =====================================================

CREATE TABLE utilizadores (
    id_utilizador INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    tipo ENUM('Administrador', 'Técnico', 'Consulta') NOT NULL DEFAULT 'Administrador',
    estado ENUM('Ativo', 'Inativo') NOT NULL DEFAULT 'Ativo',
    ultimo_acesso DATETIME NULL,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- 2. CONTEÚDO EDITÁVEL DO INDEX PÚBLICO
-- Campos correspondem à página private/views/editar/editar-index.html
-- =====================================================

CREATE TABLE conteudo_publico (
    id_conteudo INT AUTO_INCREMENT PRIMARY KEY,
    nome_sistema VARCHAR(40) NOT NULL,
    slogan VARCHAR(80) NOT NULL,
    titulo_principal VARCHAR(120) NOT NULL,
    texto_principal VARCHAR(500) NOT NULL,
    botao_principal VARCHAR(40) NOT NULL,
    imagem_capa VARCHAR(150) NULL,
    titulo_sobre VARCHAR(80) NOT NULL,
    texto_sobre VARCHAR(700) NOT NULL,

    funcionalidade_1 VARCHAR(80) NOT NULL,
    descricao_funcionalidade_1 VARCHAR(160) NOT NULL,
    funcionalidade_2 VARCHAR(80) NOT NULL,
    descricao_funcionalidade_2 VARCHAR(160) NOT NULL,
    funcionalidade_3 VARCHAR(80) NOT NULL,
    descricao_funcionalidade_3 VARCHAR(160) NOT NULL,
    funcionalidade_4 VARCHAR(80) NOT NULL,
    descricao_funcionalidade_4 VARCHAR(160) NOT NULL,

    vantagem_1 VARCHAR(80) NOT NULL,
    vantagem_2 VARCHAR(80) NOT NULL,
    vantagem_3 VARCHAR(80) NOT NULL,

    texto_rodape VARCHAR(100) NOT NULL,
    ano_rodape YEAR NOT NULL,

    data_atualizacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- 3. LOCALIZAÇÕES
-- Campos correspondem às páginas localizacoes/novo.html e localizacoes/editar.html
-- =====================================================

CREATE TABLE localizacoes (
    id_localizacao INT AUTO_INCREMENT PRIMARY KEY,
    edificio VARCHAR(40) NOT NULL,
    piso INT NOT NULL,
    servico VARCHAR(80) NOT NULL,
    sala VARCHAR(40) NOT NULL,
    tipo_area ENUM(
        'Área Crítica',
        'Bloco Operatório',
        'Diagnóstico',
        'Internamento',
        'Laboratório',
        'Armazém',
        'Administrativa',
        'Outra'
    ) NOT NULL DEFAULT 'Outra',
    estado ENUM('Ativa', 'Inativa', 'Em obras', 'Indisponível') NOT NULL DEFAULT 'Ativa',
    capacidade_equipamentos INT DEFAULT 0,
    acesso_restrito ENUM('Sim', 'Não') DEFAULT 'Não',
    prioridade_tecnica ENUM('Normal', 'Alta', 'Crítica') DEFAULT 'Normal',
    observacoes VARCHAR(500) NULL,

    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT chk_localizacao_piso CHECK (piso BETWEEN 0 AND 20),
    CONSTRAINT chk_localizacao_capacidade CHECK (capacidade_equipamentos BETWEEN 0 AND 200)
) ENGINE=InnoDB;

-- =====================================================
-- 4. FORNECEDORES
-- Campos correspondem às páginas fornecedores/novo.html e fornecedores/editar.html
-- =====================================================

CREATE TABLE fornecedores (
    id_fornecedor INT AUTO_INCREMENT PRIMARY KEY,
    nome_empresa VARCHAR(80) NOT NULL,
    nif CHAR(9) NOT NULL UNIQUE,
    estado ENUM('Ativo', 'Inativo') NOT NULL DEFAULT 'Ativo',
    tipo_fornecedor ENUM(
        'Fabricante',
        'Distribuidor / Fornecedor Comercial',
        'Empresa de Assistência Técnica',
        'Fornecedor de Consumíveis/Acessórios'
    ) NOT NULL,
    area_atuacao ENUM(
        'Diagnóstico e Imagiologia',
        'Monitorização',
        'Suporte de Vida',
        'Laboratório',
        'Consumíveis Hospitalares',
        'Assistência Técnica',
        'Outro'
    ) DEFAULT 'Outro',
    email VARCHAR(120) NOT NULL,
    telefone CHAR(9) NULL,
    website VARCHAR(150) NULL,
    pessoa_contacto VARCHAR(80) NULL,
    tel_pessoa CHAR(9) NULL,
    morada VARCHAR(120) NULL,
    contrato_ativo ENUM('Sim', 'Não') DEFAULT 'Não',
    relacao_hospital ENUM(
        'Fornecedor principal',
        'Fornecedor secundário',
        'Prestador de assistência técnica',
        'Fornecedor de consumíveis',
        'Fabricante sem contrato direto'
    ) NULL,
    prioridade_contacto ENUM('Normal', 'Alta', 'Urgente') DEFAULT 'Normal',
    observacoes VARCHAR(500) NULL,

    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT chk_fornecedor_nif CHECK (nif REGEXP '^[0-9]{9}$'),
    CONSTRAINT chk_fornecedor_telefone CHECK (telefone IS NULL OR telefone REGEXP '^[0-9]{9}$'),
    CONSTRAINT chk_fornecedor_tel_pessoa CHECK (tel_pessoa IS NULL OR tel_pessoa REGEXP '^[0-9]{9}$')
) ENGINE=InnoDB;

-- =====================================================
-- 5. EQUIPAMENTOS
-- Campos correspondem às páginas equipamentos/novo.html e equipamentos/editar.html
-- =====================================================

CREATE TABLE equipamentos (
    id_equipamento INT AUTO_INCREMENT PRIMARY KEY,
    codigo_inventario VARCHAR(30) NOT NULL UNIQUE,
    designacao VARCHAR(100) NOT NULL,
    categoria ENUM(
        'Monitorização',
        'Suporte de Vida',
        'Terapia',
        'Diagnóstico',
        'Laboratório',
        'Esterilização',
        'Reabilitação',
        'Outro'
    ) NOT NULL,
    marca VARCHAR(60) NOT NULL,
    modelo VARCHAR(60) NOT NULL,
    num_serie VARCHAR(60) NOT NULL UNIQUE,
    fabricante VARCHAR(80) NOT NULL,
    data_aquisicao DATE NOT NULL,
    ano_fabrico YEAR NOT NULL,
    custo DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    tipo_entrada ENUM('Compra', 'Doação', 'Aluguer', 'Empréstimo') NOT NULL,
    estado ENUM('Operacional', 'Em Manutenção', 'Inativo', 'Abatido') NOT NULL DEFAULT 'Operacional',
    criticidade ENUM('Baixa', 'Média', 'Alta', 'Suporte de Vida') NOT NULL,

    id_localizacao INT NULL,
    id_fornecedor_principal INT NULL,

    observacoes VARCHAR(500) NULL,

    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT chk_equipamento_custo CHECK (custo >= 0),
    CONSTRAINT chk_equipamento_ano CHECK (ano_fabrico BETWEEN 1990 AND 2100),

    CONSTRAINT fk_equipamento_localizacao
        FOREIGN KEY (id_localizacao)
        REFERENCES localizacoes(id_localizacao)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_equipamento_fornecedor_principal
        FOREIGN KEY (id_fornecedor_principal)
        REFERENCES fornecedores(id_fornecedor)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- 6. ASSOCIAÇÃO EQUIPAMENTO-FORNECEDOR
-- Corresponde à página fornecedores/associar.html
-- =====================================================

CREATE TABLE equipamento_fornecedor (
    id_equipamento_fornecedor INT AUTO_INCREMENT PRIMARY KEY,
    id_equipamento INT NOT NULL,
    id_fornecedor INT NOT NULL,
    tipo_associacao ENUM(
        'Fabricante',
        'Fornecedor',
        'Assistência Técnica',
        'Consumíveis/Acessórios',
        'Outro'
    ) NOT NULL DEFAULT 'Fornecedor',
    data_inicio DATE NULL,
    data_fim DATE NULL,
    estado ENUM('Ativo', 'Inativo') NOT NULL DEFAULT 'Ativo',
    observacoes VARCHAR(500) NULL,

    UNIQUE KEY uk_equipamento_fornecedor_tipo (id_equipamento, id_fornecedor, tipo_associacao),

    CONSTRAINT fk_eq_for_equipamento
        FOREIGN KEY (id_equipamento)
        REFERENCES equipamentos(id_equipamento)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_eq_for_fornecedor
        FOREIGN KEY (id_fornecedor)
        REFERENCES fornecedores(id_fornecedor)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- 7. GARANTIAS E CONTRATOS
-- Corresponde ao módulo garantias
-- =====================================================

CREATE TABLE garantias_contratos (
    id_garantia INT AUTO_INCREMENT PRIMARY KEY,
    id_equipamento INT NOT NULL,
    id_fornecedor INT NULL,

    tipo_contrato ENUM('Garantia', 'Preventiva', 'Corretiva', 'Full Service') NOT NULL,
    estado ENUM('Ativa', 'A Terminar', 'Expirada') NOT NULL DEFAULT 'Ativa',
    criticidade ENUM('Baixa', 'Média', 'Alta', 'Suporte de Vida') NOT NULL,

    numero_contrato VARCHAR(30) NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    custo_anual DECIMAL(10,2) DEFAULT 0.00,

    pessoa_contacto VARCHAR(80) NULL,
    telefone_contacto CHAR(9) NULL,

    documento_associado VARCHAR(100) NULL,
    caminho_documento VARCHAR(200) NULL,
    observacoes VARCHAR(500) NULL,

    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT chk_garantia_datas CHECK (data_fim >= data_inicio),
    CONSTRAINT chk_garantia_custo CHECK (custo_anual >= 0),
    CONSTRAINT chk_garantia_tel CHECK (telefone_contacto IS NULL OR telefone_contacto REGEXP '^[0-9]{9}$'),

    CONSTRAINT fk_garantia_equipamento
        FOREIGN KEY (id_equipamento)
        REFERENCES equipamentos(id_equipamento)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_garantia_fornecedor
        FOREIGN KEY (id_fornecedor)
        REFERENCES fornecedores(id_fornecedor)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- 8. DOCUMENTOS
-- Corresponde ao módulo documentacao
-- Permite associar diretamente a equipamento, fornecedor ou garantia.
-- =====================================================

CREATE TABLE documentos (
    id_documento INT AUTO_INCREMENT PRIMARY KEY,

    nome_documento VARCHAR(100) NOT NULL,
    tipo_documento ENUM(
        'Manual de Utilizador',
        'Manual de Serviço',
        'Certificado',
        'Contrato',
        'Fatura',
        'Declaração de Conformidade',
        'Relatório Técnico'
    ) NOT NULL,
    estado ENUM('Válido', 'A Terminar', 'Expirado', 'Sem Validade') NOT NULL DEFAULT 'Válido',

    data_emissao DATE NULL,
    data_validade DATE NULL,

    tipo_associacao ENUM('Equipamento', 'Fornecedor', 'Garantia', 'Contrato') NOT NULL,
    entidade_associada VARCHAR(120) NOT NULL,

    id_equipamento INT NULL,
    id_fornecedor INT NULL,
    id_garantia INT NULL,

    ficheiro VARCHAR(150) NOT NULL,
    caminho_ficheiro VARCHAR(200) NOT NULL,
    referencia VARCHAR(30) NULL,
    tamanho_bytes INT NULL,
    mime_type VARCHAR(80) NULL,

    observacoes VARCHAR(500) NULL,

    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT chk_documento_validade CHECK (data_validade IS NULL OR data_emissao IS NULL OR data_validade >= data_emissao),

    CONSTRAINT fk_documento_equipamento
        FOREIGN KEY (id_equipamento)
        REFERENCES equipamentos(id_equipamento)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_documento_fornecedor
        FOREIGN KEY (id_fornecedor)
        REFERENCES fornecedores(id_fornecedor)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_documento_garantia
        FOREIGN KEY (id_garantia)
        REFERENCES garantias_contratos(id_garantia)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- 9. MANUTENÇÕES
-- Não estava como página separada, mas completa o sistema hospitalar e liga-se a contratos/equipamentos.
-- =====================================================

CREATE TABLE manutencoes (
    id_manutencao INT AUTO_INCREMENT PRIMARY KEY,
    id_equipamento INT NOT NULL,
    id_fornecedor INT NULL,
    id_garantia INT NULL,

    tipo_manutencao ENUM('Preventiva', 'Corretiva', 'Calibração', 'Inspeção', 'Outra') NOT NULL,
    estado ENUM('Planeada', 'Em Curso', 'Concluída', 'Cancelada') NOT NULL DEFAULT 'Planeada',
    data_planeada DATE NULL,
    data_realizacao DATE NULL,
    responsavel VARCHAR(100) NULL,
    custo DECIMAL(10,2) DEFAULT 0.00,
    descricao VARCHAR(500) NULL,
    resultado VARCHAR(500) NULL,

    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT chk_manutencao_custo CHECK (custo >= 0),

    CONSTRAINT fk_manutencao_equipamento
        FOREIGN KEY (id_equipamento)
        REFERENCES equipamentos(id_equipamento)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_manutencao_fornecedor
        FOREIGN KEY (id_fornecedor)
        REFERENCES fornecedores(id_fornecedor)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_manutencao_garantia
        FOREIGN KEY (id_garantia)
        REFERENCES garantias_contratos(id_garantia)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- 10. AUDITORIA
-- Para registar alterações feitas no Back Office.
-- =====================================================

CREATE TABLE auditoria (
    id_auditoria INT AUTO_INCREMENT PRIMARY KEY,
    id_utilizador INT NULL,
    tabela_afetada VARCHAR(80) NOT NULL,
    id_registo INT NULL,
    acao ENUM('Inserir', 'Editar', 'Remover', 'Login', 'Logout') NOT NULL,
    descricao VARCHAR(500) NULL,
    data_acao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_auditoria_utilizador
        FOREIGN KEY (id_utilizador)
        REFERENCES utilizadores(id_utilizador)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- VIEWS PARA LISTAGENS
-- =====================================================

CREATE OR REPLACE VIEW vw_equipamentos_completo AS
SELECT
    e.id_equipamento,
    e.codigo_inventario,
    e.designacao,
    e.categoria,
    e.marca,
    e.modelo,
    e.num_serie,
    e.estado,
    e.criticidade,
    CONCAT(l.edificio, ' | Piso ', l.piso, ' | ', l.servico, ' | ', l.sala) AS localizacao,
    f.nome_empresa AS fornecedor_principal,
    e.data_aquisicao,
    e.custo
FROM equipamentos e
LEFT JOIN localizacoes l ON e.id_localizacao = l.id_localizacao
LEFT JOIN fornecedores f ON e.id_fornecedor_principal = f.id_fornecedor;

CREATE OR REPLACE VIEW vw_fornecedores_completo AS
SELECT
    f.id_fornecedor,
    f.nome_empresa,
    f.nif,
    f.tipo_fornecedor,
    f.email,
    f.telefone,
    f.pessoa_contacto,
    f.estado,
    COUNT(DISTINCT ef.id_equipamento) AS total_equipamentos_associados
FROM fornecedores f
LEFT JOIN equipamento_fornecedor ef ON f.id_fornecedor = ef.id_fornecedor
GROUP BY
    f.id_fornecedor,
    f.nome_empresa,
    f.nif,
    f.tipo_fornecedor,
    f.email,
    f.telefone,
    f.pessoa_contacto,
    f.estado;

CREATE OR REPLACE VIEW vw_localizacoes_completo AS
SELECT
    l.id_localizacao,
    l.edificio,
    l.piso,
    l.servico,
    l.sala,
    l.tipo_area,
    l.estado,
    l.capacidade_equipamentos,
    COUNT(e.id_equipamento) AS total_equipamentos
FROM localizacoes l
LEFT JOIN equipamentos e ON l.id_localizacao = e.id_localizacao
GROUP BY
    l.id_localizacao,
    l.edificio,
    l.piso,
    l.servico,
    l.sala,
    l.tipo_area,
    l.estado,
    l.capacidade_equipamentos;

CREATE OR REPLACE VIEW vw_garantias_completo AS
SELECT
    g.id_garantia,
    e.codigo_inventario,
    e.designacao AS equipamento,
    f.nome_empresa AS fornecedor,
    g.tipo_contrato,
    g.data_inicio,
    g.data_fim,
    g.estado,
    g.criticidade,
    g.documento_associado,
    g.custo_anual
FROM garantias_contratos g
INNER JOIN equipamentos e ON g.id_equipamento = e.id_equipamento
LEFT JOIN fornecedores f ON g.id_fornecedor = f.id_fornecedor;

CREATE OR REPLACE VIEW vw_documentos_completo AS
SELECT
    d.id_documento,
    d.tipo_documento,
    d.nome_documento,
    d.tipo_associacao,
    d.entidade_associada,
    d.data_validade,
    d.estado,
    d.ficheiro,
    d.caminho_ficheiro,
    d.referencia
FROM documentos d;

-- =====================================================
-- DADOS INICIAIS
-- =====================================================

INSERT INTO utilizadores (nome, email, password_hash, tipo, estado)
VALUES
('Administrador', 'admin@sanogest.pt', '$2y$12$KlAyZnISRa.Jp5QntaVIIuIVuT9LU4KFNuRWECei57wUWJJ0h8yXe', 'Administrador', 'Ativo');

INSERT INTO conteudo_publico (
    nome_sistema,
    slogan,
    titulo_principal,
    texto_principal,
    botao_principal,
    imagem_capa,
    titulo_sobre,
    texto_sobre,
    funcionalidade_1,
    descricao_funcionalidade_1,
    funcionalidade_2,
    descricao_funcionalidade_2,
    funcionalidade_3,
    descricao_funcionalidade_3,
    funcionalidade_4,
    descricao_funcionalidade_4,
    vantagem_1,
    vantagem_2,
    vantagem_3,
    texto_rodape,
    ano_rodape
) VALUES (
    'SanoGest',
    'Inventário Hospitalar Digital',
    'Gestão Inteligente de Equipamentos Hospitalares',
    'O SanoGest é uma plataforma digital de apoio à gestão do inventário hospitalar, permitindo centralizar informação sobre equipamentos médicos, localizações, fornecedores, documentação, garantias e contratos.',
    'Entrar no Sistema',
    'assets/img/capa-hospitalar.png',
    'Sobre o SanoGest',
    'O SanoGest foi desenvolvido para apoiar unidades hospitalares na organização e consulta de informação relacionada com equipamentos médicos. A plataforma permite melhorar o controlo dos recursos, facilitar a consulta de dados técnicos e apoiar processos de manutenção, auditoria e gestão documental.',
    'Gestão de Equipamentos',
    'Registo e consulta de equipamentos médicos hospitalares.',
    'Localizações',
    'Associação dos equipamentos a edifícios, pisos, serviços e salas.',
    'Documentação',
    'Gestão de manuais, certificados, contratos e relatórios técnicos.',
    'Garantias e Contratos',
    'Controlo de garantias, contratos de manutenção e datas de validade.',
    'Organização centralizada',
    'Consulta rápida de informação',
    'Apoio à manutenção e auditoria',
    'SanoGest - Sistema de Gestão Hospitalar',
    2026
);

INSERT INTO localizacoes (
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
) VALUES
('Edifício A', 1, 'Bloco Operatório', 'Sala 04', 'Área Crítica', 'Ativa', 10, 'Sim', 'Alta', 'Zona de alta esterilização.'),
('Edifício A', 0, 'Urgência', 'Sala de Reanimação', 'Área Crítica', 'Ativa', 12, 'Sim', 'Crítica', 'Sala destinada a situações críticas.'),
('Edifício B', 2, 'UCI', 'Box 03', 'Área Crítica', 'Ativa', 8, 'Sim', 'Crítica', 'Unidade de cuidados intensivos.'),
('Edifício B', 1, 'Radiologia', 'Sala RX 02', 'Diagnóstico', 'Ativa', 6, 'Não', 'Alta', 'Sala de exames de radiologia.'),
('Edifício C', 3, 'Laboratório', 'Lab Central', 'Laboratório', 'Ativa', 15, 'Sim', 'Normal', 'Laboratório central.');

INSERT INTO fornecedores (
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
) VALUES
('Siemens Healthineers', '500123456', 'Ativo', 'Fabricante', 'Diagnóstico e Imagiologia', 'geral@siemens.pt', '220000001', 'https://www.siemens.pt', 'Ana Martins', '910000000', 'Av. da Liberdade, Lisboa', 'Sim', 'Fornecedor principal', 'Alta', 'Fornecedor preferencial para equipamentos de diagnóstico.'),
('Dräger Portugal', '501987654', 'Ativo', 'Empresa de Assistência Técnica', 'Suporte de Vida', 'assistencia@draeger.pt', '220000002', 'https://www.draeger.pt', 'João Pereira', '920000000', 'Rua Técnica, Braga', 'Sim', 'Prestador de assistência técnica', 'Urgente', 'Assistência técnica de equipamentos críticos.'),
('Philips Healthcare Portugal', '502456789', 'Ativo', 'Distribuidor / Fornecedor Comercial', 'Monitorização', 'geral@philips.pt', '220000003', 'https://www.philips.pt', 'Carlos Ribeiro', '930000000', 'Rua da Saúde, Porto', 'Sim', 'Fornecedor principal', 'Alta', 'Fornecedor de monitores multiparamétricos.'),
('B. Braun', '503111222', 'Ativo', 'Fabricante', 'Consumíveis Hospitalares', 'geral@bbraun.pt', '220000004', 'https://www.bbraun.pt', 'Marta Silva', '940000000', 'Rua Hospitalar, Coimbra', 'Não', 'Fornecedor secundário', 'Normal', 'Fornecedor de bombas de infusão.'),
('Medtronic', '504222333', 'Ativo', 'Distribuidor / Fornecedor Comercial', 'Suporte de Vida', 'geral@medtronic.pt', '220000005', 'https://www.medtronic.pt', 'Pedro Costa', '950000000', 'Rua Médica, Lisboa', 'Não', 'Fornecedor secundário', 'Normal', 'Fornecedor adicional.');

INSERT INTO equipamentos (
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
    id_fornecedor_principal,
    observacoes
) VALUES
('EQP-001', 'Ventilador Pulmonar', 'Suporte de Vida', 'Dräger', 'Evita V500', 'SN-V500-001', 'Dräger', '2026-01-01', 2025, 18000.00, 'Compra', 'Operacional', 'Suporte de Vida', 1, 2, 'Equipamento crítico de suporte ventilatório.'),
('EQP-002', 'Monitor Multiparamétrico', 'Monitorização', 'Philips', 'IntelliVue MP5', 'SN-MP5-002', 'Philips', '2024-07-15', 2024, 6500.00, 'Compra', 'Operacional', 'Alta', 2, 3, 'Monitor usado na urgência.'),
('EQP-003', 'Bomba de Infusão', 'Terapia', 'B. Braun', 'Infusomat Space', 'SN-INF-003', 'B. Braun', '2023-01-01', 2022, 2500.00, 'Compra', 'Em Manutenção', 'Média', 3, 4, 'Equipamento em manutenção corretiva.'),
('EQP-004', 'Desfibrilhador', 'Suporte de Vida', 'Zoll', 'R Series', 'SN-ZOLL-004', 'Zoll', '2025-03-10', 2025, 9000.00, 'Compra', 'Operacional', 'Alta', 2, 2, 'Equipamento usado em emergência.'),
('EQP-005', 'Eletrocardiógrafo', 'Diagnóstico', 'GE', 'MAC 2000', 'SN-GE-005', 'GE Healthcare', '2022-06-05', 2021, 3200.00, 'Compra', 'Inativo', 'Baixa', 4, 1, 'Equipamento temporariamente inativo.'),
('EQP-006', 'Centrífuga', 'Laboratório', 'Eppendorf', '5810 R', 'SN-EP-006', 'Eppendorf', '2023-09-12', 2023, 7800.00, 'Compra', 'Operacional', 'Média', 5, 5, 'Equipamento laboratorial.');

INSERT INTO equipamento_fornecedor (
    id_equipamento,
    id_fornecedor,
    tipo_associacao,
    data_inicio,
    estado,
    observacoes
) VALUES
(1, 2, 'Fabricante', '2026-01-01', 'Ativo', 'Fabricante e assistência técnica principal.'),
(1, 1, 'Fornecedor', '2026-01-01', 'Ativo', 'Fornecedor administrativo associado.'),
(2, 3, 'Fornecedor', '2024-07-15', 'Ativo', 'Fornecedor principal do monitor.'),
(3, 4, 'Fabricante', '2023-01-01', 'Ativo', 'Fabricante da bomba de infusão.'),
(4, 2, 'Assistência Técnica', '2025-03-10', 'Ativo', 'Assistência técnica do desfibrilhador.');

INSERT INTO garantias_contratos (
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
) VALUES
(1, 1, 'Preventiva', 'Ativa', 'Suporte de Vida', 'CON-2026-001', '2026-01-01', '2028-01-01', 2500.00, 'Ana Martins', '910000000', 'contrato_eqp001.pdf', 'assets/docs/contrato_eqp001.pdf', 'Contrato de manutenção preventiva para equipamento crítico de suporte ventilatório.'),
(2, 3, 'Garantia', 'A Terminar', 'Alta', 'GAR-2024-002', '2024-07-15', '2026-07-15', 0.00, 'Carlos Ribeiro', '930000000', 'garantia_eqp002.pdf', 'assets/docs/garantia_eqp002.pdf', 'Garantia do monitor multiparamétrico.'),
(4, 2, 'Full Service', 'Ativa', 'Alta', 'FS-2025-004', '2025-03-10', '2029-03-10', 3000.00, 'João Pereira', '920000000', 'fullservice_eqp004.pdf', 'assets/docs/fullservice_eqp004.pdf', 'Contrato full service do desfibrilhador.'),
(3, 4, 'Corretiva', 'Expirada', 'Média', 'COR-2023-003', '2023-01-01', '2025-01-01', 900.00, 'Marta Silva', '940000000', 'contrato_eqp003.pdf', 'assets/docs/contrato_eqp003.pdf', 'Contrato corretivo expirado.');

INSERT INTO documentos (
    nome_documento,
    tipo_documento,
    estado,
    data_emissao,
    data_validade,
    tipo_associacao,
    entidade_associada,
    id_equipamento,
    id_fornecedor,
    id_garantia,
    ficheiro,
    caminho_ficheiro,
    referencia,
    tamanho_bytes,
    mime_type,
    observacoes
) VALUES
('Calibração_Vent_V500', 'Certificado', 'Válido', '2026-05-20', '2027-05-20', 'Equipamento', 'EQP-001 | Ventilador Pulmonar', 1, NULL, NULL, 'calibracao_vent_v500.pdf', 'assets/docs/calibracao_vent_v500.pdf', 'DOC-2026-001', 245760, 'application/pdf', 'Certificado de calibração associado ao ventilador pulmonar V500.'),
('Manual_Monitor_IntelliVue_MP5', 'Manual de Utilizador', 'Sem Validade', '2024-07-15', NULL, 'Equipamento', 'EQP-002 | Monitor Philips', 2, NULL, NULL, 'manual_monitor_mp5.pdf', 'assets/docs/manual_monitor_mp5.pdf', 'DOC-2024-002', 1024000, 'application/pdf', 'Manual de utilização do monitor multiparamétrico.'),
('Contrato_Manutencao_EQP004', 'Contrato', 'A Terminar', '2025-03-10', '2026-07-15', 'Contrato', 'Desfibrilhador EQP-004 | Dräger', 4, 2, 3, 'contrato_eqp004.pdf', 'assets/docs/contrato_eqp004.pdf', 'DOC-2025-004', 512000, 'application/pdf', 'Contrato de manutenção do desfibrilhador.'),
('Relatorio_Tecnico_Bomba_Infusao', 'Relatório Técnico', 'Expirado', '2023-01-01', '2025-01-01', 'Equipamento', 'EQP-003 | Bomba de Infusão', 3, 4, 4, 'relatorio_bomba_infusao.pdf', 'assets/docs/relatorio_bomba_infusao.pdf', 'DOC-2023-003', 300000, 'application/pdf', 'Relatório técnico da bomba de infusão.'),
('Declaracao_Conformidade_Siemens', 'Declaração de Conformidade', 'Válido', '2026-01-01', '2028-12-31', 'Fornecedor', 'Siemens Healthineers', NULL, 1, NULL, 'declaracao_siemens.pdf', 'assets/docs/declaracao_siemens.pdf', 'DOC-2026-005', 150000, 'application/pdf', 'Declaração de conformidade do fornecedor Siemens.');

INSERT INTO manutencoes (
    id_equipamento,
    id_fornecedor,
    id_garantia,
    tipo_manutencao,
    estado,
    data_planeada,
    data_realizacao,
    responsavel,
    custo,
    descricao,
    resultado
) VALUES
(1, 1, 1, 'Preventiva', 'Planeada', '2026-12-15', NULL, 'Ana Martins', 0.00, 'Manutenção preventiva anual do ventilador.', NULL),
(3, 4, 4, 'Corretiva', 'Em Curso', '2026-06-01', NULL, 'Marta Silva', 450.00, 'Intervenção corretiva na bomba de infusão.', 'A aguardar peça de substituição.'),
(2, 3, 2, 'Inspeção', 'Concluída', '2026-03-20', '2026-03-20', 'Carlos Ribeiro', 0.00, 'Inspeção técnica do monitor.', 'Equipamento em conformidade.');

INSERT INTO auditoria (
    id_utilizador,
    tabela_afetada,
    id_registo,
    acao,
    descricao
) VALUES
(1, 'sistema', NULL, 'Login', 'Dados iniciais importados para teste.');

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- FIM DO SCRIPT
-- =====================================================
