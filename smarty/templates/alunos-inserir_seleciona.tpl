<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Seleciona aluno</title>
<link rel="stylesheet" href="../../estagio.css">
<link rel="stylesheet" href="../../estagio-moderno.css">

{literal}
<script language="Javascript" type="text/javascript">
function seleciona()
{
    var id_valor = document.seleciona_aluno.aluno_id.value;
    document.location.href="inserir.php?aluno_id=" + id_valor;
    return false;
}
</script>
{/literal}
</head>

<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Alunos</p>
			<h1>Selecionar aluno<span>Escolha um aluno para inserir estágio</span></h1>
		</div>
	</header>

	<section class="table-shell">
		<form name="seleciona_aluno" action="inserir.php" method="post" class="periodo-form">
			<label for="aluno_id">Aluno</label>
			<select name="aluno_id" id="aluno_id" size="1" onChange="return seleciona();">
				<option value=0>Selecione aluno</option>
				{section name=elemento loop=$alunos}
				<option value={$alunos[elemento].aluno_id}>{$alunos[elemento].nome}</option>
				{/section}
			</select>
			<button type="submit" name="selecionaAluno" class="btn btn-sm">Confirma</button>
		</form>
	</section>

</main>

</body>
</html>
