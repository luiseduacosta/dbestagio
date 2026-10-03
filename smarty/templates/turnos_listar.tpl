<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Turnos</title>
</head>
<body>

<a href="javascript:history.back();">Voltar</a><br>

<div align="center">
<h3>Lista de Turnos</h3>
</div>

<table id="turnos" class="display">
<thead>
<tr>
<th>Id</th>
<th>Turno</th>
<th>Alunos</th>
<th>Ações</th>
</tr>
</thead>
<tbody>

{section name=i loop=$turnos}
<tr>
<td class="coluna_direita">{$turnos[i].id}</td>
<td><a href="ver_cada.php?id={$turnos[i].id}">{$turnos[i].turno}</a></td>
<td class="coluna_direita">{$turnos[i].num_alunos}</td>
<td>
<a href="ver_cada.php?id={$turnos[i].id}">Ver</a> |
<a href="../atualizar/modifica.php?id={$turnos[i].id}">Editar</a>
</td>
</tr>
{/section}

</tbody>
</table>

<div align="center">
<p><a href="../inserir/formulario.php">Inserir novo turno</a></p>
</div>

{literal}
<script src="../../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#turnos').DataTable({
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
