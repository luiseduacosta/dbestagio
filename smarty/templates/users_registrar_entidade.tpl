<!doctype html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Vincular perfil — ESS/UFRJ</title>
	<link rel="stylesheet" href="../estagio.css">
	<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

	<main class="mural-page" style="max-width: 640px; margin: 40px auto;">

		<header class="page-head">
			<div class="page-heading">
				<p class="eyebrow">Passo 2 de 2 · Cadastro</p>
				<h1>Vincular perfil de {$role_label}</h1>
				<span>{$usuario_nome} ({$usuario_email})</span>
			</div>
		</header>

		{if $sucesso}
		<div class="flash-message">
			{$sucesso}
			<p style="margin-top:12px;">
				<a href="../login.php" style="color:var(--mural-success); font-weight:600;">Ir para o login</a>
			</p>
		</div>
		{elseif $ja_vinculado}
		<div class="flash-message" style="border-color:#B9DCC5; background:var(--mural-success-soft); color:var(--mural-success);">
			Sua conta já está vinculada a um perfil de {$role_label}.
			<p style="margin-top:12px;">
				<a href="../login.php" style="color:var(--mural-success); font-weight:600;">Ir para o login</a>
			</p>
		</div>
		{else}

			{if $erro}
			<div class="flash-message" style="border-color:#E8CFA8; background:var(--mural-warning-soft); color:var(--mural-warning);">
				{$erro}
			</div>
			{/if}

			{* --- Busca por entidade existente --- *}
			<div class="table-shell" style="margin-bottom:16px;">
				<h2 style="font-size:18px; margin:0 0 12px;">Já tem cadastro como {$role_label}?</h2>
				<p style="color:var(--mural-muted); margin:0 0 12px; font-size:14px;">
					Busque pelo seu nome ou CPF. Se encontrar seu registro, selecione-o para vincular à sua conta.
				</p>
				<form method="post" action="registrar_entidade.php?user_id={$user_id}">
					<input type="hidden" name="acao" value="buscar">
					<p style="margin:0 0 8px;">
						<input type="text" name="busca_nome" placeholder="Nome"
							style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit; margin-bottom:8px;">
						<input type="text" name="busca_cpf" placeholder="CPF (somente números)"
							style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit;">
					</p>
					<p style="text-align:center; margin:12px 0 0;">
						<button type="submit" style="min-height:40px; padding:0 24px; border:none; border-radius:6px; background:var(--mural-accent); color:#fff; font:inherit; font-weight:600; cursor:pointer;">Buscar</button>
					</p>
				</form>
			</div>

			{* --- Resultados da busca --- *}
			{if $resultados}
			<div class="table-shell" style="margin-bottom:16px;">
				<h2 style="font-size:18px; margin:0 0 12px;">Resultados encontrados</h2>
				{section name=r loop=$resultados}
				<div style="padding:12px; border:1px solid var(--mural-line); border-radius:6px; margin-bottom:8px; display:flex; justify-content:space-between; align-items:center;">
					<div>
						<strong>{$resultados[r].nome}</strong>
						{if $resultados[r].cpf}<br><span style="color:var(--mural-muted); font-size:13px;">CPF: {$resultados[r].cpf}</span>{/if}
						{if $resultados[r].email}<br><span style="color:var(--mural-muted); font-size:13px;">{$resultados[r].email}</span>{/if}
						{if $role == 'aluno' && $resultados[r].registro}<br><span style="color:var(--mural-muted); font-size:13px;">Registro: {$resultados[r].registro}</span>{/if}
						{if $role == 'professor' && $resultados[r].siape}<br><span style="color:var(--mural-muted); font-size:13px;">SIAPE: {$resultados[r].siape}</span>{/if}
						{if $role == 'supervisor' && $resultados[r].cress}<br><span style="color:var(--mural-muted); font-size:13px;">CRESS: {$resultados[r].cress}</span>{/if}
					</div>
					<div>
						{if $resultados[r].ja_vinculada}
							<span style="color:var(--mural-warning); font-size:13px;">Já vinculado</span>
						{else}
							<form method="post" action="registrar_entidade.php?user_id={$user_id}" style="margin:0;">
								<input type="hidden" name="acao" value="vincular">
								<input type="hidden" name="entidade_id" value="{$resultados[r].id}">
								<button type="submit" style="min-height:36px; padding:0 16px; border:1px solid var(--mural-accent); border-radius:6px; background:var(--mural-soft); color:var(--mural-accent); font:inherit; font-weight:600; cursor:pointer;">Vincular</button>
							</form>
						{/if}
					</div>
				</div>
				{/section}
			</div>

			{* Link para criar novo *}
			<div style="text-align:center; margin-bottom:16px;">
				<form method="post" action="registrar_entidade.php?user_id={$user_id}" style="display:inline;">
					<input type="hidden" name="acao" value="criar_novo">
					<button type="submit" style="min-height:36px; padding:0 16px; border:none; background:none; color:var(--mural-muted); font:inherit; text-decoration:underline; cursor:pointer;">Não encontrei meu cadastro — criar novo</button>
				</form>
			</div>

			{* --- Formulário de criação de nova entidade --- *}
			{elseif $mostrar_form_criar}
			<div class="table-shell">
				<h2 style="font-size:18px; margin:0 0 12px;">Criar novo perfil de {$role_label}</h2>
				<p style="color:var(--mural-muted); margin:0 0 12px; font-size:14px;">
					Preencha seus dados. Eles serão vinculados à sua conta.
				</p>
				<form method="post" action="registrar_entidade.php?user_id={$user_id}">
					<input type="hidden" name="acao" value="criar">

					<p style="margin:0 0 12px;">
						<label style="display:block; font-weight:600; margin-bottom:4px;">Nome *</label>
						<input type="text" name="ent_nome" required value="{$usuario_nome}"
							style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit;">
					</p>

					<p style="margin:0 0 12px;">
						<label style="display:block; font-weight:600; margin-bottom:4px;">CPF</label>
						<input type="text" name="ent_cpf"
							style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit;">
					</p>

					<p style="margin:0 0 12px;">
						<label style="display:block; font-weight:600; margin-bottom:4px;">E-mail</label>
						<input type="email" name="ent_email" value="{$usuario_email}"
							style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit;">
					</p>

					{if $role == 'aluno'}
					<p style="margin:0 0 12px;">
						<label style="display:block; font-weight:600; margin-bottom:4px;">Registro (matrícula)</label>
						<input type="text" name="ent_registro"
							style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit;">
					</p>
					<p style="margin:0 0 12px;">
						<label style="display:block; font-weight:600; margin-bottom:4px;">Telefone</label>
						<input type="text" name="ent_telefone"
							style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit;">
					</p>
					{/if}

					{if $role == 'professor'}
					<p style="margin:0 0 12px;">
						<label style="display:block; font-weight:600; margin-bottom:4px;">SIAPE</label>
						<input type="text" name="ent_siape"
							style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit;">
					</p>
					{/if}

					{if $role == 'supervisor'}
					<p style="margin:0 0 12px;">
						<label style="display:block; font-weight:600; margin-bottom:4px;">CRESS</label>
						<input type="text" name="ent_cress"
							style="width:100%; min-height:40px; padding:0 12px; border:1px solid var(--mural-line-strong); border-radius:6px; font:inherit;">
					</p>
					{/if}

					<p style="text-align:center; margin:0;">
						<button type="submit" style="min-height:40px; padding:0 24px; border:none; border-radius:6px; background:var(--mural-accent); color:#fff; font:inherit; font-weight:600; cursor:pointer;">Criar e vincular</button>
					</p>
				</form>
			</div>

			{* Botão para criar novo quando não há resultados *}
			{elseif $acao == 'buscar' && empty($resultados) && empty($erro)}
			<div style="text-align:center; margin-top:16px;">
				<p style="color:var(--mural-muted); margin-bottom:12px;">Nenhum registro encontrado.</p>
				<form method="post" action="registrar_entidade.php?user_id={$user_id}" style="display:inline;">
					<input type="hidden" name="acao" value="criar_novo">
					<button type="submit" style="min-height:40px; padding:0 24px; border:none; border-radius:6px; background:var(--mural-accent); color:#fff; font:inherit; font-weight:600; cursor:pointer;">Criar novo perfil de {$role_label}</button>
				</form>
			</div>
			{/if}

		{/if}

	</main>

</body>
</html>
