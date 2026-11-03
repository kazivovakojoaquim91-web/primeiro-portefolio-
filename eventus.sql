-- ========================================================
-- Eventus - Portal de Anúncios de Eventos
-- Estrutura da Base de Dados
-- ========================================================

-- 1. Criação da base de dados
CREATE DATABASE IF NOT EXISTS eventus_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE eventus_db;

-- 2. Tabela de eventos (com suporte a imagem)
CREATE TABLE IF NOT EXISTS eventos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(150) NOT NULL,
  descricao TEXT NOT NULL,
  data_evento DATE NOT NULL,
  local VARCHAR(150) NOT NULL,
  imagem VARCHAR(255) DEFAULT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Exemplos iniciais para teste
INSERT INTO eventos (titulo, descricao, data_evento, local, imagem) VALUES
('Festival de Música & Arte', 'Dois dias de apresentações culturais, gastronomia ao ar livre e bandas ao vivo.', '2026-11-20', 'Parque Central', NULL),
('Conferência de Tecnologia e Inovação', 'Palestras sobre inteligência artificial, transformação digital e novas tendências no mercado.', '2026-12-05', 'Centro de Convenções', NULL),
('Workshop de Fotografia Urbana', 'Aprenda técnicas de composição, iluminação natural e fotografia prática nas ruas da cidade.', '2026-12-18', 'Espaço Criativo', NULL);

-- 4. Consulta de listagem
SELECT id, titulo, descricao, data_evento, local, imagem
FROM eventos
ORDER BY data_evento ASC;
