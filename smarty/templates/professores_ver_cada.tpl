<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Professor - {$professor_nome}</title>
</head>
<body>

<a href="javascript:history.back();">Voltar</a>
<div align="center">
<h3>Professor: {$professor_nome}</h3>
<p>{$num_estagios} estágio(s) | {$num_instituicoes} instituição(ões)</p>
</div>

{if $instituicoes}
<div align="center">
<table border="1" width="60%">
<caption>Instituições</caption>
<tbody>
{foreach item=i from=$instituicoes}
<tr>
<td><a href="../../instituicoes/exibir/ver_cada.php?id_instituicao={$i.id}">{$i.instituicao}</a></td>
</tr>
{/foreach}
</tbody>
</table>
</div>
{/if}

<div align="center">
<table id="estagiarios" class="display" width="95%">
<thead>
<tr>
<th><a href="?professor_id={$professor_id}&ordem=a.registro">Registro</a></th>
<th><a href="?professor_id={$professor_id}&ordem=a.nome">Aluno</a></th>
<th><a href="?professor_id={$professor_id}&ordem=e.periodo">Período</a></th>
<th>Nível</th>
<th><a href="?professor_id={$professor_id}&ordem=i.instituicao">Instituição</a></th>
<th>Área</th>
</tr>
</thead>
<tbody>

{foreach item=e from=$estagiarios}
<tr>
<td class="coluna_direita">{$e.registro}</td>
<td><a href="../../alunos/exibir/ver_cada.php?aluno_id={$e.aluno_id}">{$e.aluno_nome}</a></td>
<td class="coluna_centralizada">{$e.periodo}</td>
<td class="coluna_centralizada">{$e.nivel}</td>
<td>{$e.instituicao}</td>
<td>{$e.area}</td>
</tr>
{/foreach}

</tbody>
</table>
</div>

<div align="center">
<p><a href="../atualizar/atualiza.php?professor_id={$professor_id}">Editar dados do professor</a></p>
</div>

{literal}
<script src="../../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#estagiarios').DataTable({
		language: { url: '../../libjs/datatables/pt-BR.json' },
		pageLength: 25,
		lengthMenu: [10, 25, 50, 100],
		order: []
	});
});
</script>
{/literal}

</body>
</html>