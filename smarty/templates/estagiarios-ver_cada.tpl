<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Estágio #{$e.id} — Estágio ESS/UFRJ</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Estagiários</p>
			<h1>Estágio #{$e.id}<span>{$e.aluno_nome}{if $e.periodo} · {$e.periodo}{/if}</span></h1>
		</div>
	</header>

	<table class="ficha card-table">
	<tbody>

	<tr>
	<th>Período</th>
	<td>{$e.periodo}</td>
	</tr>

	<tr>
	<th>Aluno</th>
	<td>
	<a href="../alunos/ver_cada.php?aluno_id={$e.aluno_id}">{$e.aluno_nome}</a>
	({$e.registro})
	</td>
	</tr>

	<tr>
	<th>Instituição</th>
	<td>
	{if $e.instituicao_id}
	<a href="../instituicoes/ver_cada.php?id_instituicao={$e.instituicao_id}">{$e.instituicao}</a>
	{else}{$e.instituicao}{/if}
	</td>
	</tr>

	<tr><th>Supervisor</th><td>{$e.supervisor}</td></tr>

	<tr><th>Professor</th><td>{$e.professor}</td></tr>

	<tr><th>Nível</th><td>{$e.nivel}</td></tr>

	<tr><th>TC</th><td>{$e.tc}</td></tr>

	<tr><th>TC solicitação</th><td>{$e.tc_solicitacao}</td></tr>

	<tr><th>Nota</th><td>{$e.nota}</td></tr>

	<tr><th>Carga horária</th><td>{$e.ch}</td></tr>

	<tr><th>Ajuste 2020</th><td>{$e.ajuste2020}</td></tr>

	<tr><th>Transporte</th><td>{if $e.benetransporte}Sim{else}Não{/if}</td></tr>

	<tr><th>Alimentação</th><td>{if $e.benealimentacao}Sim{else}Não{/if}</td></tr>

	<tr><th>Bolsa</th><td>{$e.benebolsa}</td></tr>

	<tr><th>Observações</th><td>{$e.observacoes}</td></tr>

	</tbody>
	</table>

	<div class="action-row">
		{if $isAdmin}
		<a class="action-btn" href="atualizar.php?id={$e.id}">Editar estágio</a>
		{/if}
		<a class="btn btn-secondary" href="listar.php">Voltar para a listagem</a>
	</div>

</main>

</body>
</html>
