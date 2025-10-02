CREATE DATABASE IF NOT EXISTS marktour DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE marktour;

-- TABELA DE LOCALIZAÇÃO
CREATE TABLE localizacao (
    id_localizacao INT AUTO_INCREMENT PRIMARY KEY,
    provincia VARCHAR(50) NOT NULL,
    distrito VARCHAR(50) NOT NULL,
    bairro VARCHAR(50) NOT NULL,
    posto_administrativo VARCHAR(100),
    localidade VARCHAR(100),
    avenida VARCHAR(100),
    rua VARCHAR(100),
    andar VARCHAR(20),
    endereco_detalhado TEXT,
    codigo_postal VARCHAR(10)
);

ALTER TABLE sessao
ADD COLUMN data DATE,
ADD COLUMN hora_inicio TIME,
ADD COLUMN hora_fim TIME,
ADD COLUMN token VARCHAR(255),
ADD COLUMN se_valido TINYINT(1),
ADD COLUMN utilization_id INT,
ADD COLUMN createdAt DATETIME,
ADD COLUMN updatedAt DATETIME,
ADD COLUMN ip_address VARCHAR(255),
ADD COLUMN user_agent TEXT;

alter table sessao 
drop column data_inicio,
drop column data_fim;

-- TABELA DE UTILIZADOR
CREATE TABLE utilizador (
    id_utilizador INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    tipo ENUM('cliente', 'empresa', 'admin') NOT NULL,
    data_registo DATETIME DEFAULT CURRENT_TIMESTAMP
);

insert into utilizador(nome, email, senha, telefone, tipo) value ('Pedro Jorge', 'pedro@gmail.com', '1234', '850731919', 'admin');
insert into utilizador(nome, email, senha, telefone, tipo) value ('Pedro Jorge', 'pedrojorge@gmail.com', '1234', '850731919', 'cliente');

-- TABELA DE EMPRESA
CREATE TABLE empresa (
    id_empresa INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    nuit VARCHAR(20) NOT NULL,
    descricao TEXT,
    estado_verificacao ENUM('pendente', 'aprovado', 'rejeitado') DEFAULT 'pendente',
    data_registo DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_utilizador INT NOT NULL,
    id_localizacao INT,
    FOREIGN KEY (id_utilizador) REFERENCES utilizador(id_utilizador),
    FOREIGN KEY (id_localizacao) REFERENCES localizacao(id_localizacao)
);

-- TABELA DE ALOJAMENTO
CREATE TABLE alojamento (
    id_alojamento INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(50),
    descricao TEXT,
    preco_noite DECIMAL(10,2),
    num_quartos INT,
    id_empresa INT,
    FOREIGN KEY (id_empresa) REFERENCES empresa(id_empresa)
);

-- TABELA DE VIAGEM
CREATE TABLE viagem (
    id_viagem INT AUTO_INCREMENT PRIMARY KEY,
    origem VARCHAR(100) NOT NULL,
    destino VARCHAR(100) NOT NULL,
    data_partida DATE,
    data_retorno DATE,
    descricao TEXT,
    preco DECIMAL(10,2),
    id_empresa INT,
    FOREIGN KEY (id_empresa) REFERENCES empresa(id_empresa)
);

-- TABELA DE ACTIVIDADE
CREATE TABLE actividade (
    id_actividade INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    local VARCHAR(100),
    data_hora DATETIME,
    duracao VARCHAR(50),
    preco DECIMAL(10,2),
    id_empresa INT,
    FOREIGN KEY (id_empresa) REFERENCES empresa(id_empresa)
);

-- TABELA DE RESERVA
CREATE TABLE reserva (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    data_reserva DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendente', 'confirmada', 'cancelada') DEFAULT 'pendente',
    total DECIMAL(10,2),
    id_utilizador INT,
    FOREIGN KEY (id_utilizador) REFERENCES utilizador(id_utilizador)
);

-- TABELA DE ITENS DE RESERVA
CREATE TABLE reserva_item (
    id_reserva_item INT AUTO_INCREMENT PRIMARY KEY,
    tipo_item ENUM('viagem', 'alojamento', 'actividade') NOT NULL,
    id_item INT NOT NULL,
    quantidade INT DEFAULT 1,
    preco_unitario DECIMAL(10,2),
    id_reserva INT,
    FOREIGN KEY (id_reserva) REFERENCES reserva(id_reserva)
);

-- TABELA DE PAGAMENTO
CREATE TABLE pagamento (
    id_pagamento INT AUTO_INCREMENT PRIMARY KEY,
    metodo_pagamento ENUM('Mpesa', 'e-Mola', 'Transferência', 'Cartão') NOT NULL,
    valor DECIMAL(10,2),
    data_pagamento DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pago', 'pendente', 'recusado') DEFAULT 'pendente',
    id_reserva INT,
    FOREIGN KEY (id_reserva) REFERENCES reserva(id_reserva)
);

-- TABELA DE AVALIAÇÃO
CREATE TABLE avaliacao (
    id_avaliacao INT AUTO_INCREMENT PRIMARY KEY,
    tipo_item ENUM('viagem', 'alojamento', 'actividade') NOT NULL,
    id_item INT NOT NULL,
    comentario TEXT,
    classificacao INT CHECK (classificacao BETWEEN 1 AND 5),
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_utilizador INT,
    FOREIGN KEY (id_utilizador) REFERENCES utilizador(id_utilizador)
);

-- TABELA DE FAQ
CREATE TABLE faq (
    id_faq INT AUTO_INCREMENT PRIMARY KEY,
    pergunta TEXT NOT NULL,
    resposta TEXT NOT NULL,
    categoria VARCHAR(50),
    visivel BOOLEAN DEFAULT TRUE,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultima_atualizacao DATETIME
);

-- TABELA DE COMENTÁRIOS
CREATE TABLE comentario (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    tipo_item ENUM('viagem', 'alojamento', 'actividade') NOT NULL,
    id_item INT NOT NULL,
    comentario TEXT NOT NULL,
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_utilizador INT,
    status ENUM('aprovado', 'pendente', 'rejeitado') DEFAULT 'pendente',
    FOREIGN KEY (id_utilizador) REFERENCES utilizador(id_utilizador)
);

CREATE TABLE sessao (
    id_sessao INT AUTO_INCREMENT PRIMARY KEY,
    id_utilizador INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    token_valido tinyint,
    ip VARCHAR(45),  -- IPv6 suporta até 45 caracteres
    user_agent TEXT,
    data_inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_fim DATETIME,
    estado ENUM('ativa', 'expirada', 'terminada') DEFAULT 'ativa',
    FOREIGN KEY (id_utilizador) REFERENCES utilizador(id_utilizador)
);

CREATE TABLE contacto_empresa (
    id_contacto INT AUTO_INCREMENT PRIMARY KEY,
    telefone1 VARCHAR(20),
    telefone2 VARCHAR(20),
    telefone3 VARCHAR(20),
    fax1 VARCHAR(20),
    fax2 VARCHAR(20),
    email VARCHAR(100),
    website VARCHAR(100),
    id_empresa INT NOT NULL UNIQUE,
    FOREIGN KEY (id_empresa) REFERENCES empresa(id_empresa)
);


CREATE TABLE actividade_utilizador (
    id_actividade INT AUTO_INCREMENT PRIMARY KEY,
    id_sessao INT NOT NULL,
    acao VARCHAR(100) NOT NULL,     -- Ex: login, reserva criada, visualização
    descricao TEXT,                 -- Detalhes da ação
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_sessao) REFERENCES sessao(id_sessao)
);

alter table actividade_utilizador
add tipo text, 
add duracao int;

ALTER TABLE reserva ADD COLUMN id_pagamento INT, ADD FOREIGN KEY (id_pagamento) REFERENCES pagamento(id_pagamento);
ALTER TABLE pagamento ADD COLUMN referencia_stripe VARCHAR(255);
CREATE TABLE notificacao (id_notificacao INT AUTO_INCREMENT PRIMARY KEY, id_utilizador INT, mensagem TEXT, lida TINYINT(1) DEFAULT 0, data DATETIME DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (id_utilizador) REFERENCES utilizador(id_utilizador));
ALTER TABLE reserva ADD COLUMN data_checkin DATE, ADD COLUMN data_checkout DATE;
CREATE TABLE admin_aprovacao(id_aprovacao INT AUTO_INCREMENT PRIMARY KEY, id_reserva INT, id_admin INT, data_aprovacao DATETIME, FOREIGN KEY (id_reserva) REFERENCES reserva(id_reserva), FOREIGN KEY (id_admin) REFERENCES utilizador(id_utilizador));
ALTER TABLE reserva ADD CONSTRAINT chk_datas CHECK (data_checkout > data_checkin);

ALTER TABLE alojamento
ADD COLUMN imagem_path VARCHAR(255) DEFAULT NULL;

ALTER TABLE empresa
ADD COLUMN imagem_nuit_path VARCHAR(255) DEFAULT NULL,
ADD COLUMN imagem_alvara_path VARCHAR(255) DEFAULT NULL,
ADD COLUMN numAlvara VARCHAR(255) DEFAULT NULL;