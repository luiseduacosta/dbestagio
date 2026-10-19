<?php
/**
 * index.php — Shell HTML5 do sistema de estágio.
 *
 * Substitui o frameset HTML 4.01 de index.html: um único iframe (name="_corpo")
 * para o conteúdo e um menu lateral em <details>/<summary> renderizado por
 * links.php. A autenticação é resolvida aqui (links.php recebe $isAdmin pronto
 * e não inclui mais autentica.inc, que redirecionaria visitantes anônimos).
 */
require_once(__DIR__ . "/setup.php");
require_once(__DIR__ . "/libphp/models.php");
require_once(__DIR__ . "/libphp/sessao.php");

// Detect login state.
// sessao_usuario_atual() relê o usuário da tabela users a cada requisição e
// descarta a sessão se ele estiver desativado/removido — mesmo critério usado
// por autentica.inc nas demais páginas do sistema.
$sessao = sessao_usuario_atual();
$usuario_logado = ($sessao !== null);
$usuario_nome = $usuario_logado ? $sessao['nome'] : '';

// Set $isAdmin for links.php
$isAdmin = false;
if ($usuario_logado) {
    // 'role' vem mapeado de sessao_usuario_atual() a partir da coluna real
    // `categoria` (a tabela users não tem coluna `role`).
    if (isset($sessao['role']) && $sessao['role'] === 'admin') {
        $isAdmin = true;
    }
}

// Determine initial iframe content.
// Unauthenticated users land on the mural listing (current period) so they
// can see available offers without logging in. Login is required only for
// actions (add/edit/delete/inscribe).
$pagina_inicial = $usuario_logado ? 'capa_logado.php' : 'mural/mural-listar.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Banco de dados da Coordenação de Estágio</title>
    <link rel="stylesheet" href="app.css">
</head>
<body>

<header class="app-header">
    <div class="header-left">
        <span class="app-title">Coordenação de Estágio — ESS/UFRJ</span>
    </div>
    <div class="header-right">
        <?php if ($usuario_logado) { ?>
            <span class="user-name">Olá, <?php echo htmlspecialchars($usuario_nome); ?></span>
            <a href="logout.php" class="btn-logout">Sair</a>
        <?php } else { ?>
            <a href="login.php" target="_corpo" class="btn-login">Entrar</a>
        <?php } ?>
        <button class="hamburger" id="menuToggle" aria-label="Menu" aria-expanded="false" aria-controls="sidebar">&#9776;</button>
    </div>
</header>

<div class="app-shell">
    <main class="content-frame">
        <iframe name="_corpo" id="_corpo" src="<?php echo $pagina_inicial; ?>" title="Conteúdo do sistema"></iframe>
    </main>
    <aside class="sidebar" id="sidebar">
        <?php include __DIR__ . '/links.php'; ?>
    </aside>
</div>

<script>
document.getElementById('menuToggle').addEventListener('click', function () {
    var sidebar = document.getElementById('sidebar');
    var aberto = sidebar.classList.toggle('open');
    this.setAttribute('aria-expanded', aberto ? 'true' : 'false');
});
</script>

</body>
</html>
