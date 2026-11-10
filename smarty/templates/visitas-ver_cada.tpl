<<<<<<< HEAD
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Ver visita</title>
</head>
<body>

<div align="center">
<h3>Dados da visita</h3>
</div>

<table class="ficha">
<tbody>
<tr><th>Data</th><td>{$v.data}</td></tr>
<tr><th>Instituição</th><td>{$nome_instituicao}</td></tr>
<tr><th>Professor</th><td>{$nome_professor}</td></tr>
<tr><th>Motivo</th><td>{$v.motivo}</td></tr>
<tr><th>Responsável</th><td>{$v.responsavel}</td></tr>
<tr><th>Avaliação</th><td>{$v.avaliacao}</td></tr>
<tr><th>Descrição</th><td>{$v.descricao}</td></tr>
</tbody>
</table>

<div align="center">
<p>
<a href="atualizar.php?id={$v.id}">Editar</a> |
<a href="javascript:history.back()">Voltar</a> |
<a href="excluir.php?id={$v.id}" onclick="return confirm('Excluir esta visita?');">Excluir</a>
</p>
</div>

</body>
</html>
=======
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ver visita</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Visitas</p>
			<h1>Dados da visita<span>{$nome_instituicao}</span></h1>
		</div>
	</header>

	<table class="ficha card-table">
	<tbody>
		<tr><th>Data</th><td>{$v.data}</td></tr>
		<tr><th>Instituição</th><td>{$nome_instituicao}</td></tr>
		<tr><th>Professor</th><td>{if $nome_professor}{$nome_professor}{else}—{/if}</td></tr>
		<tr><th>Motivo</th><td>{$v.motivo}</td></tr>
		<tr><th>Responsável</th><td>{$v.responsavel}</td></tr>
		<tr><th>Avaliação</th><td>{$v.avaliacao}</td></tr>
		<tr><th>Descrição</th><td>{if $v.descricao}{$v.descricao}{else}—{/if}</td></tr>
	</tbody>
	</table>

	<div class="action-row">
		<a class="btn" href="atualizar.php?id={$v.id}">Editar</a>
		<a class="btn btn-secondary" href="listar.php">Voltar para a listagem</a>
		<a class="btn btn-danger" href="excluir.php?id={$v.id}" onclick="return confirm('Excluir esta visita?');">Excluir</a>
	</div>

</main>

</body>
</html>
>>>>>>> 73b82345dbc2a4ee8d41cc4ec84448ffcf7e91fa
