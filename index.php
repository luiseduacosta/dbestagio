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

// Set role flags for links.php.
// 'role' vem mapeado de sessao_usuario_atual() a partir da coluna real
// `categoria` (a tabela users não tem coluna `role`).
$role = ($usuario_logado && isset($sessao['role'])) ? $sessao['role'] : '';
$isAdmin = ($role === 'admin');
$isAluno = ($role === 'aluno');
$isProfessor = ($role === 'professor');

// Id da entidade do próprio usuário (para o link "Meu cadastro" no menu).
$usuario_professor_id = ($usuario_logado && isset($sessao['professor_id'])) ? (int)$sessao['professor_id'] : 0;

// Determine initial iframe content.
// Unauthenticated users land on the mural listing (current period) so they
// can see available offers without logging in. Login is required only for
// actions (add/edit/delete/inscribe).
$pagina_inicial = $usuario_logado ? 'capa_logado.php' : 'mural/mural-listar.php';

// Impersonação: admin visualizando como outro usuário
$admin_real_id = $usuario_logado ? sessao_admin_real() : 0;
$imprimindo = ($admin_real_id > 0);

// Se voltando de impersonação, carrega a ficha da entidade no iframe
// em vez de capa_logado.php. O destino é definido por assumir() na sessão.
if ($imprimindo && !empty($_SESSION['impersonacao_destino'])) {
    $pagina_inicial = $_SESSION['impersonacao_destino'];
    unset($_SESSION['impersonacao_destino']);
}
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
            <?php if ($imprimindo) { ?>
                <span class="impersonation-banner" style="background:#fff3cd; color:#856404; padding:4px 12px; border-radius:4px; font-size:0.85em;">
                    Visualizando como: <?php echo htmlspecialchars($usuario_nome); ?>
                </span>
                <a href="users/desassumir.php" class="btn-logout" style="background:#dc3545;">Voltar como admin</a>
            <?php } else { ?>
                <span class="user-name">Olá, <?php echo htmlspecialchars($usuario_nome); ?></span>
                <a href="logout.php" class="btn-logout">Sair</a>
            <?php } ?>
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
