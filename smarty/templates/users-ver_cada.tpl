<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ver usuário</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Administração · Usuários</p>
			<h1>{$u.nome}<span>{$u.email}</span></h1>
		</div>
	</header>

	<table class="ficha card-table">
	<tbody>
	<tr><th>E-mail</th><td>{$u.email}</td></tr>
	<tr><th>Nome</th><td>{$u.nome}</td></tr>
	<tr><th>Role</th><td>{$u.role_texto}</td></tr>
	<tr><th>Categoria</th><td>{$u.categoria_texto}</td></tr>
	<tr><th>Identificação</th><td>{$u.identificacao}</td></tr>
	<tr><th>Situação</th><td>{if $u.ativo == 1}<span class="convenio-badge sim">Ativo</span>{else}<span class="convenio-badge nao">Inativo</span>{/if}</td></tr>
	{if $u.aluno}
	<tr><td colspan="2">Dados do Aluno associado</td></tr>
	<tr><th>ID Aluno</th><td>{$u.aluno.id}</td></tr>
	{if $u.aluno.nome}        <tr><th>Nome</th><td><a href="../alunos/ver_cada.php?aluno_id={$u.aluno.id}">{$u.aluno.nome}</a></td></tr>{/if}
	{if $u.aluno.registro}    <tr><th>Registro</th><td>{$u.aluno.registro}</td></tr>{/if}
	{if $u.aluno.cpf}         <tr><th>CPF</th><td>{$u.aluno.cpf}</td></tr>{/if}
	{if $u.aluno.email}       <tr><th>E-mail</th><td>{$u.aluno.email}</td></tr>{/if}
	{if $u.aluno.turno_nome}  <tr><th>Turno</th><td>{$u.aluno.turno_nome}</td></tr>{/if}
	{if $u.aluno.telefone}    <tr><th>Telefone</th><td>{$u.aluno.telefone}</td></tr>{/if}
	{if $u.aluno.endereco}    <tr><th>Endereço</th><td>{$u.aluno.endereco}</td></tr>{/if}
	{/if}
	{if $u.supervisor}
	<tr><td colspan="2">Dados do Supervisor associado</td></tr>
	<tr><th>ID Supervisor</th><td>{$u.supervisor.id}</td></tr>
	{if $u.supervisor.nome}     <tr><th>Nome</th><td><a href="../supervisores/ver_cada.php?supervisor_id={$u.supervisor.id}">{$u.supervisor.nome}</a></td></tr>{/if}
	{if $u.supervisor.cpf}      <tr><th>CPF</th><td>{$u.supervisor.cpf}</td></tr>{/if}
	{if $u.supervisor.cress}    <tr><th>CRESS</th><td>{$u.supervisor.cress} / {$u.supervisor.regiao}</td></tr>{/if}
	{if $u.supervisor.email}    <tr><th>E-mail</th><td>{$u.supervisor.email}</td></tr>{/if}
	{if $u.supervisor.cargo}    <tr><th>Cargo</th><td>{$u.supervisor.cargo}</td></tr>{/if}
	{if $u.supervisor.escola}   <tr><th>Escola de formação</th><td>{$u.supervisor.escola}</td></tr>{/if}
	{if $u.supervisor.telefone || $u.supervisor.celular}
	<tr><th>Contato</th><td>
	{if $u.supervisor.codigo_telefone}({$u.supervisor.codigo_telefone}) {/if}{$u.supervisor.telefone}
	{if $u.supervisor.celular} / {if $u.supervisor.codigo_celular}({$u.supervisor.codigo_celular}) {/if}{$u.supervisor.celular}{/if}
	</td></tr>
	{/if}
	{if $u.supervisor.municipio}<tr><th>Município</th><td>{$u.supervisor.endereco}{if $u.supervisor.bairro} - {$u.supervisor.bairro}{/if}{if $u.supervisor.municipio} - {$u.supervisor.municipio}{/if}</td></tr>{/if}
	{/if}
	{if $u.professor}
	<tr><td colspan="2">Dados do Professor associado</td></tr>
	<tr><th>ID Professor</th><td>{$u.professor.id}</td></tr>
	{if $u.professor.nome}     <tr><th>Nome</th><td><a href="../professores/ver_cada.php?professor_id={$u.professor.id}">{$u.professor.nome}</a></td></tr>{/if}
	{if $u.professor.cpf}      <tr><th>CPF</th><td>{$u.professor.cpf}</td></tr>{/if}
	{if $u.professor.siape}    <tr><th>SIAPE</th><td>{$u.professor.siape}</td></tr>{/if}
	{if $u.professor.cress}    <tr><th>CRESS</th><td>{$u.professor.cress} / {$u.professor.regiao}</td></tr>{/if}
	{if $u.professor.email}    <tr><th>E-mail</th><td>{$u.professor.email}</td></tr>{/if}
	{if $u.professor.departamento}<tr><th>Departamento</th><td>{$u.professor.departamento}</td></tr>{/if}
	{if $u.professor.tipocargo} <tr><th>Tipo de cargo</th><td>{$u.professor.tipocargo}</td></tr>{/if}
	{if $u.professor.dataingresso}<tr><th>Data de ingresso</th><td>{$u.professor.dataingresso}</td></tr>{/if}
	{if $u.professor.curriculolattes}<tr><th>Curriculo Lattes</th><td><a href="{$u.professor.curriculolattes}" target="_blank">{$u.professor.curriculolattes}</a></td></tr>{/if}
	{/if}
	</tbody>
	</table>

	<div class="action-row">
		<a class="action-btn" href="atualizar.php?id={$u.id}">Editar</a>
		<a class="btn btn-secondary" href="javascript:history.back()">Voltar</a>
		<a class="btn btn-danger" href="excluir.php?id={$u.id}" onclick="return confirm('Excluir este usuário?');">Excluir</a>
		{if $is_admin && $u.role != 'admin'}
		<a class="btn btn-secondary" href="assumir.php?id={$u.id}" onclick="return confirm('Assumir a identidade deste usuário?');">Assumir identidade</a>
		{/if}
	</div>

</main>

</body>
</html>
