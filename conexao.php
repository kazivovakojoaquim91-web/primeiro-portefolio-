<?php
/*
  conexao.php
  Ligação à base de dados MySQL utilizando PDO
  Projeto: Eventus - Anúncios de Eventos
*/

$host = "localhost";
$db = "eventus_db";
$user = "root";
$password = ""; // Senha padrão no ambiente local XAMPP

try {
    // 1. Cria a base de dados eventus_db se ainda não existir
    $dsnInicial = "mysql:host=$host;charset=utf8mb4";
    $pdoInicial = new PDO($dsnInicial, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $pdoInicial->exec("CREATE DATABASE IF NOT EXISTS eventus_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // 2. Conexão principal com a base de dados
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // 3. Cria a tabela de eventos com a coluna imagem
    $pdo->exec("CREATE TABLE IF NOT EXISTS eventos (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(150) NOT NULL,
        descricao TEXT NOT NULL,
        data_evento DATE NOT NULL,
        local VARCHAR(150) NOT NULL,
        imagem VARCHAR(255) DEFAULT NULL,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 4. Garante que a coluna imagem existe se a tabela já existia anteriormente
    try {
        $pdo->query("SELECT imagem FROM eventos LIMIT 1");
    } catch (Exception $e) {
        $pdo->exec("ALTER TABLE eventos ADD COLUMN imagem VARCHAR(255) DEFAULT NULL AFTER local");
    }

    // 5. Se a tabela estiver vazia, insere anúncios de exemplo
    $stmtCount = $pdo->query("SELECT COUNT(*) AS total FROM eventos");
    if ($stmtCount->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO eventos (titulo, descricao, data_evento, local, imagem) VALUES 
            ('Festival de Música & Arte', 'Dois dias de apresentações culturais, gastronomia ao ar livre e bandas ao vivo.', '2026-11-20', 'Parque Central', NULL),
            ('Conferência de Tecnologia e Inovação', 'Palestras sobre inteligência artificial, transformação digital e novas tendências no mercado.', '2026-12-05', 'Centro de Convenções', NULL),
            ('Workshop de Fotografia Urbana', 'Aprenda técnicas de composição, iluminação natural e fotografia prática nas ruas da cidade.', '2026-12-18', 'Espaço Criativo', NULL)
        ");
    }

} catch (PDOException $e) {
    ?>
    <!DOCTYPE html>
    <html lang="pt">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Erro de Ligação - Eventus</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">
        <div class="bg-white border-l-4 border-red-500 rounded-xl p-6 shadow-md max-w-lg w-full">
            <h2 class="text-xl font-bold text-red-600 mb-2">Erro de Ligação à Base de Dados</h2>
            <p class="text-slate-700 mb-4">
                Não foi possível conectar ao MySQL. Certifique-se de que o serviço <strong>MySQL</strong> está iniciado no <strong>XAMPP Control Panel</strong>.
            </p>
            <div class="bg-slate-50 p-3 rounded text-xs text-slate-600 font-mono">
                Mensagem: <?= htmlspecialchars($e->getMessage()) ?>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>
