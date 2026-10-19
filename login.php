<?php
// Encerro a sessão anterior (quem chega ao formulário de login entra de novo)
require_once(__DIR__ . "/libphp/sessao.php");
sessao_encerrar();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login — Coordenação de Estágio ESS/UFRJ</title>
	<link rel="stylesheet" href="estagio.css">
	<link rel="stylesheet" href="estagio-moderno.css">
</head>
<body id="corpo">
	<main class="mural-page" style="max-width: 480px; margin: 60px auto;">
		<header class="page-head">
			<div class="page-heading">
				<p class="eyebrow">Coordenação de Estágio e Extensão · ESS/UFRJ</p>
				<h1>Acessar o sistema</h1>
			</div>
		</header>

		<div class="table-shell">
			<form name="login" id="login" action="verifica_login.php?opcao=capa" method="post">
				<p style="margin: 0 0 12px;">
					<label for="email" style="display:block; font-weight:600; margin-bottom:4px;">E-mail</label>
					<input type="text" name="email" id="email" required
						style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit;">
				</p>
				<p style="margin: 0 0 16px;">
					<label for="password" style="display:block; font-weight:600; margin-bottom:4px;">Senha</label>
					<input type="password" name="password" id="password" required
						style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit;">
				</p>
				<p style="text-align:center; margin:0;">
					<button type="submit" style="min-height:40px; padding:0 24px; border:none; border-radius:6px; background:var(--mural-accent); color:#fff; font:inherit; font-weight:600; cursor:pointer;">Entrar</button>
				</p>
			</form>
		</div>

		<p style="text-align:center; margin-top:16px;">
			Não tem conta? <a href="users/registrar.php" style="color:var(--mural-accent); font-weight:600;">Cadastrar-se</a>
		</p>
	</main>
</body>
</html>
