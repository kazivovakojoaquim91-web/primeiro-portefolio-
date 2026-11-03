<?php
// Ligação à base de dados MySQL com PDO
require __DIR__ . "/conexao.php";

$erros = [];
$mensagem = "";

$titulo = "";
$descricao = "";
$data = "";
$local = "";

// Processa o envio do formulário via método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = trim($_POST["titulo"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");
    $data = trim($_POST["data_evento"] ?? "");
    $local = trim($_POST["local"] ?? "");

    // 1. Validação simples de campos de texto obrigatórios
    if ($titulo === "" || $descricao === "" || $data === "" || $local === "") {
        $erros[] = "Preencha todos os campos obrigatórios (*).";
    }

    // 2. Upload simples da imagem do evento (opcional)
    $nomeImagem = null;
    if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($_FILES["imagem"]["name"], PATHINFO_EXTENSION));
        $extensoesPermitidas = ["jpg", "jpeg", "png", "webp"];

        if (in_array($extensao, $extensoesPermitidas)) {
            // Gera um nome único simples para evitar substituir arquivos com o mesmo nome
            $nomeImagem = uniqid("evento_") . "." . $extensao;
            $destino = __DIR__ . "/uploads/" . $nomeImagem;

            // Move o arquivo temporário para a pasta uploads
            if (!move_uploaded_file($_FILES["imagem"]["tmp_name"], $destino)) {
                $erros[] = "Não foi possível salvar a imagem na pasta uploads.";
                $nomeImagem = null;
            }
        } else {
            $erros[] = "Formato de imagem inválido. Use JPG, PNG ou WEBP.";
        }
    }

    // 3. Se não houver erros, insere o evento no banco de dados
    if (!$erros) {
        $sql = "INSERT INTO eventos (titulo, descricao, data_evento, local, imagem)
                VALUES (:titulo, :descricao, :data_evento, :local, :imagem)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":titulo" => $titulo,
            ":descricao" => $descricao,
            ":data_evento" => $data,
            ":local" => $local,
            ":imagem" => $nomeImagem,
        ]);

        $mensagem = "Evento publicado com sucesso!";

        // Limpa as variáveis para novos cadastros
        $titulo = "";
        $descricao = "";
        $data = "";
        $local = "";
    }
}
?>
<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Publicar Evento - Eventus</title>

    <!-- Folha de estilo nativa -->
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    <!-- Cabeçalho: Logo à esquerda e Menu alinhado à direita com ícones -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-20 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-2.5 flex items-center justify-between">
            
            <!-- Logo: Imagem retangular -->
            <a href="index.php" class="flex items-center">
                <img src="assets/images/logo.png" alt="Eventus" class="h-10 w-auto object-contain">
            </a>

            <!-- Menu de navegação à direita -->
            <nav class="flex items-center gap-6 text-sm font-medium">
                <!-- Início -->
                <a href="index.php" class="flex items-center gap-1.5 text-slate-600 hover:text-indigo-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Início</span>
                </a>

                <!-- Sobre -->
                <a href="index.php#sobre" class="flex items-center gap-1.5 text-slate-600 hover:text-indigo-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Sobre</span>
                </a>

                <!-- Publicar Evento -->
                <a href="publicar.php" class="flex items-center gap-1.5 text-indigo-600 font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Publicar evento</span>
                </a>
            </nav>

        </div>
    </header>

    <!-- Conteúdo principal -->
    <main class="max-w-xl mx-auto px-4 py-8 flex-1 w-full">

        <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-sm">
            
            <div class="mb-6">
                <span class="inline-block bg-indigo-50 text-indigo-700 text-xs font-semibold px-2.5 py-1 rounded-md mb-2">
                    Novo Anúncio
                </span>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Publicar Evento</h1>
                <p class="text-slate-600 text-sm mt-1">Preencha os detalhes para divulgar o seu evento no Eventus.</p>
            </div>

            <!-- Alerta de sucesso -->
            <?php if (!empty($mensagem)): ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-3.5 rounded-lg mb-5 flex justify-between items-center">
                    <span class="flex items-center gap-1.5 font-medium">
                        ✓ <?= htmlspecialchars($mensagem) ?>
                    </span>
                    <a href="index.php" class="underline font-bold hover:text-emerald-950">
                        Ver anúncios &rarr;
                    </a>
                </div>
            <?php endif; ?>

            <!-- Alerta de erros -->
            <?php if (!empty($erros)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm p-3.5 rounded-lg mb-5">
                    <?php foreach ($erros as $erro): ?>
                        <p class="font-medium"><?= htmlspecialchars($erro) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Formulário com suporte a envio de arquivos (enctype multipart/form-data) -->
            <form action="publicar.php" method="post" enctype="multipart/form-data" class="space-y-4">
                
                <!-- Título -->
                <div>
                    <label for="titulo" class="block text-sm font-semibold text-slate-700 mb-1">
                        Título do Evento *
                    </label>
                    <input 
                        type="text" 
                        id="titulo" 
                        name="titulo" 
                        value="<?= htmlspecialchars($titulo) ?>"
                        placeholder="Ex: Festival Gastronômico da Cidade" 
                        required 
                        class="w-full border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:outline-none focus:border-indigo-600 transition"
                    >
                </div>

                <!-- Data e Local lado a lado -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="data_evento" class="block text-sm font-semibold text-slate-700 mb-1">
                            Data do Evento *
                        </label>
                        <input 
                            type="date" 
                            id="data_evento" 
                            name="data_evento" 
                            value="<?= htmlspecialchars($data) ?>"
                            required 
                            class="w-full border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:outline-none focus:border-indigo-600 transition"
                        >
                    </div>

                    <div>
                        <label for="local" class="block text-sm font-semibold text-slate-700 mb-1">
                            Local do Evento *
                        </label>
                        <input 
                            type="text" 
                            id="local" 
                            name="local" 
                            value="<?= htmlspecialchars($local) ?>"
                            placeholder="Ex: Centro de Convenções ou Online" 
                            required 
                            class="w-full border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:outline-none focus:border-indigo-600 transition"
                        >
                    </div>
                </div>

                <!-- Campo para Upload de Imagem -->
                <div>
                    <label for="imagem" class="block text-sm font-semibold text-slate-700 mb-1">
                        Imagem do Evento <span class="text-xs font-normal text-slate-500">(Opcional)</span>
                    </label>
                    <input 
                        type="file" 
                        id="imagem" 
                        name="imagem" 
                        accept="image/png, image/jpeg, image/jpg, image/webp"
                        class="w-full border border-slate-300 rounded-lg p-2 text-sm text-slate-700 bg-slate-50 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                    >
                    <p class="text-xs text-slate-400 mt-1">Formatos aceitos: JPG, PNG ou WEBP. A imagem será guardada na pasta uploads/.</p>
                </div>

                <!-- Descrição -->
                <div>
                    <label for="descricao" class="block text-sm font-semibold text-slate-700 mb-1">
                        Descrição do Evento *
                    </label>
                    <textarea 
                        id="descricao" 
                        name="descricao" 
                        rows="4" 
                        placeholder="Detalhes sobre a programação, horários, atrações e informações para participantes..." 
                        required 
                        class="w-full border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:outline-none focus:border-indigo-600 transition"
                    ><?= htmlspecialchars($descricao) ?></textarea>
                </div>

                <!-- Botões -->
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <a href="index.php" class="text-sm font-medium text-slate-500 hover:text-slate-800">
                        Cancelar
                    </a>

                    <button 
                        type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition"
                    >
                        Publicar Evento
                    </button>
                </div>

            </form>

        </div>

    </main>

    <!-- Rodapé simples com copyright e ano actual -->
    <footer class="bg-white border-t border-slate-200 py-5 mt-auto">
        <div class="max-w-6xl mx-auto px-4 text-center text-xs text-slate-500">
            &copy; <?= date("Y") ?> Eventus. Todos os direitos reservados.
        </div>
    </footer>

</body>
</html>
