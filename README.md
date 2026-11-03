# Eventus 📅

Portal simples de anúncios e divulgação de eventos.

O projeto permite listar eventos cadastrados no banco de dados MySQL (com fotos anunciadas) e publicar novos anúncios com suporte a upload de imagem.

---

## 🛠️ Tecnologias Utilizadas

- **HTML5**: Estrutura semântica (`<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<form>`, `<footer>`).
- **CSS3 Tradicional**: Folha de estilos nativa (`assets/css/styles.css`).
- **Tailwind CSS (CDN)**: Classes utilitárias para layout responsivo (`grid md:grid-cols-2 lg:grid-cols-3`), alinhamento e componentes.
- **PHP 8**: Processamento de formulários, validação no servidor, upload com `move_uploaded_file` e proteção contra XSS com `htmlspecialchars()`.
- **MySQL**: Base de dados `eventus_db` com tabela `eventos` (incluindo coluna `imagem`).
- **PDO**: Consultas preparadas (`prepared statements`) contra SQL Injection.

---

## 📁 Estrutura do Projeto

```text
site/
├── index.php           # Página inicial com Hero Full-Width, Sobre e Listagem de eventos
├── publicar.php    # Formulário para publicação de novos eventos (com upload)
├── conexao.php              # Ligação à base de dados com PDO
├── schema.sql          # Estrutura SQL da tabela eventos e dados iniciais
├── uploads/            # Pasta onde as imagens enviadas pelos utilizadores são guardadas
├── assets/
│   ├── css/
│   │   └── styles.css  # Folha de estilo nativa
│   └── images/
│       ├── logo.png    # Logomarca retangular Eventus
│       └── hero.jpg    # Banner principal Full-Width
├── README.md           # Documentação do projeto
└── .gitignore          # Arquivos ignorados pelo Git
```

---

## 🚀 Como Executar no XAMPP

1. Coloque a pasta do projeto em `C:\xampp\htdocs\site\`.
2. Abra o **XAMPP Control Panel**.
3. Inicie os módulos **Apache** e **MySQL**.
4. Abra o navegador e acesse:
   - **Página Inicial:** `http://localhost/site/`
   - **Publicar Evento:** `http://localhost/site/publicar.php`
