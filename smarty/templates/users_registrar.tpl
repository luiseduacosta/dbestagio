<!doctype html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cadastro de novo usuário — ESS/UFRJ</title>
	<link rel="stylesheet" href="../estagio.css">
	<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

	<main class="mural-page" style="max-width: 520px; margin: 40px auto;">

		<header class="page-head">
			<div class="page-heading">
				<p class="eyebrow">Passo 1 de 2 · Cadastro</p>
				<h1>Criar conta</h1>
				<span>Seus dados de acesso ao sistema</span>
			</div>
		</header>

		{if $erro}
		<div class="flash-message" style="border-color: #E8CFA8; background: var(--mural-warning-soft); color: var(--mural-warning);">
			{$erro}
		</div>
		{/if}

		<div class="table-shell">
			<form method="post" action="registrar.php">

				<p style="margin:0 0 12px;">
					<label for="nome" style="display:block; font-weight:600; margin-bottom:4px;">Nome completo *</label>
					<input type="text" name="nome" id="nome" required
						style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit;">
				</p>

				<p style="margin:0 0 12px;">
					<label for="email" style="display:block; font-weight:600; margin-bottom:4px;">E-mail *</label>
					<input type="email" name="email" id="email" required
						style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit;">
				</p>

				<p style="margin:0 0 12px;">
					<label for="password" style="display:block; font-weight:600; margin-bottom:4px;">Senha * (mínimo 6 caracteres)</label>
					<input type="password" name="password" id="password" required minlength="6"
						style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit;">
				</p>

				<p style="margin:0 0 12px;">
					<label for="password2" style="display:block; font-weight:600; margin-bottom:4px;">Confirmar senha *</label>
					<input type="password" name="password2" id="password2" required
						style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit;">
				</p>

				<p style="margin:0 0 16px;">
					<label for="role" style="display:block; font-weight:600; margin-bottom:4px;">Tipo de usuário *</label>
					<select name="role" id="role" required
						style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit;">
						<option value="">— Selecione —</option>
						<option value="aluno">Aluno</option>
						<option value="professor">Professor</option>
						<option value="supervisor">Supervisor</option>
					</select>
				</p>

				<p style="text-align:center; margin:0;">
					<button type="submit" style="min-height:40px; padding:0 24px; border:none; border-radius:6px; background:var(--mural-accent); color:#fff; font:inherit; font-weight:600; cursor:pointer;">Continuar</button>
				</p>

			</form>
		</div>

		<p style="text-align:center; margin-top:16px;">
			Já tem conta? <a href="../login.php" style="color:var(--mural-accent); font-weight:600;">Entrar</a>
		</p>

	</main>

</body>
</html>
