<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ver inscrição #{$ins.id}</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Inscrições</p>
			<h1>Inscrição #{$ins.id}<span>{$ins.oferta_instituicao}{if $ins.periodo} · {$ins.periodo}{/if}</span></h1>
		</div>
	</header>

	<table class="ficha card-table">
	<tbody>
		<tr><th>Registro</th><td>{$ins.registro}</td></tr>
		<tr>
			<th>Aluno</th>
			<td>
				{$ins.aluno_nome}
				{if $ins.aluno_email}<span class="form-hint">{$ins.aluno_email}</span>{/if}
			</td>
		</tr>
		<tr><th>Oferta (instituição)</th><td>{$ins.oferta_instituicao}</td></tr>
		<tr><th>Período</th><td>{$ins.periodo}</td></tr>
		<tr><th>Data da inscrição</th><td>{$ins.data}</td></tr>
		<tr><th>Última atualização</th><td>{$ins.timestamp}</td></tr>
	</tbody>
	</table>

	<div class="action-row">
		<a class="btn" href="atualizar.php?id={$ins.id}">Editar</a>
		<a class="btn btn-danger" href="excluir.php?id={$ins.id}&periodo={$ins.periodo}&muralestagio_id={$ins.muralestagio_id}" onclick="return confirm('Excluir esta inscrição?');">Excluir</a>
		<a class="btn btn-secondary" href="listar.php?periodo={$ins.periodo}&muralestagio_id={$ins.muralestagio_id}">Voltar</a>
	</div>

</main>

</body>
</html>
