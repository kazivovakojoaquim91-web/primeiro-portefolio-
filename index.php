<?php
// Ligação à base de dados MySQL com PDO
require __DIR__ . "/conexao.php";

// Consulta os eventos cadastrados em ordem de data (incluindo a imagem)
$stmt = $pdo->query(
    "SELECT id, titulo, descricao, data_evento, local, imagem
     FROM eventos ORDER BY data_evento ASC"
);
$eventos = $stmt->fetchAll();
?>
<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eventus - Anúncios de Eventos</title>

    <!-- Folha de estilo nativa -->
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    <!-- Cabeçalho: Logo à esquerda (apenas imagem retangular) e Menu alinhado à direita com ícones -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-20 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-2.5 flex items-center justify-between">
            
            <!-- Logo: Apenas imagem retangular -->
            <a href="index.php" class="flex items-center">
                <img src="assets/images/logo.png" alt="Eventus" class="h-10 w-auto object-contain">
            </a>

            <!-- Menu de navegação alinhado à direita -->
            <nav class="flex items-center gap-6 text-sm font-medium">
                <!-- Início -->
                <a href="index.php" class="flex items-center gap-1.5 text-indigo-600 font-semibold">
                    <span>Início</span>
                </a>

                <!-- Sobre -->
                <a href="#sobre" class="flex items-center gap-1.5 text-slate-600 hover:text-indigo-600 transition">
                    <span>Sobre</span>
                </a>

                <!-- Publicar Evento -->
                <a href="publicar.php" class="flex items-center gap-1.5 text-slate-600 hover:text-indigo-600 transition">
                    <span>Publicar evento</span>
                </a>
            </nav>

        </div>
    </header>

    <!-- Secção Hero em toda a largura da página (Full-Width com imagem já contendo o texto) -->
    <section class="w-full relative bg-slate-900 shadow-sm overflow-hidden">
        <img 
            src="assets/images/hero.jpg" 
            alt="Eventus - Descubra e Anuncie os Melhores Eventos" 
            class="w-full h-64 sm:h-80 md:h-[400px] lg:h-[460px] object-cover"
        >
    </section>

    <!-- Conteúdo principal -->
    <main class="max-w-6xl mx-auto px-4 py-8 flex-1 w-full space-y-8">

        <!-- Secção Sobre -->
        <section id="sobre" class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900 mb-2">Sobre o Eventus</h2>
            <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                O <strong>Eventus</strong> é uma plataforma simples e aberta criada para divulgação e descoberta de eventos 
                de todos os tipos: conferências, workshops, festivais musicais, palestras de negócios e encontros culturais. 
                Conectamos organizadores e participantes de maneira rápida, direta e eficiente.
            </p>
        </section>

        <!-- Secção de Listagem de Eventos -->
        <section id="eventos">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Eventos em Destaque</h2>
                    <p class="text-xs sm:text-sm text-slate-500">Confira a programação anunciada</p>
                </div>
                <a href="publicar.php" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm">
                    + Publicar anúncio
                </a>
            </div>

            <?php if (count($eventos) > 0): ?>
                <!-- Grelha de cartões de eventos -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($eventos as $evento): 
                        $dataFormatada = date("d/m/Y", strtotime($evento["data_evento"]));
                    ?>
                        <!-- Cartão do evento -->
                        <article class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow transition flex flex-col justify-between">
                            <div>
                                <!-- Imagem do evento (se existir na pasta uploads) -->
                                <?php if (!empty($evento["imagem"]) && file_exists(__DIR__ . "/uploads/" . $evento["imagem"])): ?>
                                    <div class="w-full h-44 bg-slate-100 overflow-hidden">
                                        <img 
                                            src="uploads/<?= htmlspecialchars($evento["imagem"]) ?>" 
                                            alt="<?= htmlspecialchars($evento["titulo"]) ?>" 
                                            class="w-full h-full object-cover hover:scale-105 transition duration-300"
                                        >
                                    </div>
                                <?php endif; ?>

                                <div class="p-5">
                                    <h3 class="text-base font-bold text-slate-900 mb-2">
                                        <?= htmlspecialchars($evento["titulo"]) ?>
                                    </h3>
                                    <p class="text-slate-600 text-sm mb-4 leading-relaxed">
                                        <?= nl2br(htmlspecialchars($evento["descricao"])) ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Rodapé do cartão: Local à esquerda e Data à direita (no lugar de Confirmado) -->
                            <div class="px-5 pb-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                <span>📍 <?= htmlspecialchars($evento["local"]) ?></span>
                                <span class="font-semibold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded">
                                    <?= $dataFormatada ?>
                                </span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <!-- Mensagem quando não há eventos -->
                <div class="bg-white border border-slate-200 rounded-xl p-8 text-center text-slate-500 text-sm">
                    <p>Ainda não há eventos anunciados.</p>
                    <a href="publicar.php" class="inline-block mt-3 text-indigo-600 font-semibold hover:underline">
                        Seja o primeiro a publicar um evento &rarr;
                    </a>
                </div>
            <?php endif; ?>
        </section>

    </main>

    <!-- Rodapé simples com copyright e ano actual -->
    <footer class="bg-white border-t border-slate-200 py-5 mt-auto">
        <div class="max-w-6xl mx-auto px-4 text-center text-xs text-slate-500">
            &copy; <?= date("Y") ?> Eventus. Todos os direitos reservados.
        </div>
    </footer>

</body>
</html>
