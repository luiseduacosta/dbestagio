<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Ver Área</title>
</head>
<body>
<div align="center">
<table id="navegacao" border="0">
<tbody>
<tr>

{if $primeiro_id}
<td>
<form action="ver_cada.php" method="get">
<input type="hidden" name="area_id" value="{$primeiro_id}">
<input type="submit" value="Primeiro">
</form>
</td>
{/if}

{if $menos_10_id}
<td>
<form action="ver_cada.php" method="get">
<input type="hidden" name="area_id" value="{$menos_10_id}">
<input type="submit" value="-10">
</form>
</td>
{/if}

{if $anterior_id}
<td>
<form action="ver_cada.php" method="get">
<input type="hidden" name="area_id" value="{$anterior_id}">
<input type="submit" value="Retroceder">
</form>
</td>
{/if}

{if $proximo_id}
<td>
<form action="ver_cada.php" method="get">
<input type="hidden" name="area_id" value="{$proximo_id}">
<input type="submit" value="Avançar">
</form>
</td>
{/if}

{if $mais_10_id}
<td>
<form action="ver_cada.php" method="get">
<input type="hidden" name="area_id" value="{$mais_10_id}">
<input type="submit" value="+10">
</form>
</td>
{/if}

{if $ultimo_id}
<td>
<form action="ver_cada.php" method="get">
<input type="hidden" name="area_id" value="{$ultimo_id}">
<input type="submit" value="Último">
</form>
</td>
{/if}

<td>
<form action="../inserir/form_inserir.php" method="get">
<input type="submit" value="Inserir">
</form>
</td>

<td>
<form action="../atualizar/modifica.php" method="get">
<input type="hidden" name="area_id" value="{$area_id}">
<input type="submit" value="Modificar">
</form>
</td>

<td style="background-color:red">
<form action="../cancelar/cancela.php" method="get" onClick="return confirm('Tem certeza?');">
<input type="hidden" name="area_id" value="{$area_id}">
<input type="submit" value="Excluir">
</form>
</td>

</tr>
</tbody>
</table>
</div>

<a href="javascript:history.back();">Voltar</a><br>

<div align="center">
<h3>Visualizando Área: {$area.area}</h3>
</div>

<table class="ficha">
<tbody>
<tr><th>Id</th><td>{$area.id}</td></tr>
<tr><th>Área</th><td>{$area.area}</td></tr>
<tr><th>Instituições</th><td>{$num_instituicoes}</td></tr>
<tr>
<th>Ações</th>
<td>
<a href="../atualizar/modifica.php?area_id={$area.id}">Editar</a> |
<a href="../cancelar/cancela.php?area_id={$area.id}">Excluir</a>
</td>
</tr>
</tbody>
</table>

{if $instituicoes}
<div align="center">
<h3>Instituições nesta Área</h3>
</div>

<table id="instituicoes" class="display">
<thead>
<tr>
<th>Instituição</th>
<th>Turma</th>
<th>Supervisores</th>
<th>Endereço</th>
<th>Telefone</th>
</tr>
</thead>
<tbody>
{section name=i loop=$instituicoes}
<tr>
<td><a href="../../instituicoes/exibir/ver_cada.php?instituicao_id={$instituicoes[i].id}">{$instituicoes[i].instituicao}</a></td>
<td><a href="../../alunos/exibir/listar.php?seleciona_instituicao={$instituicoes[i].id}&seleciona_periodo={$instituicoes[i].turma}">{$instituicoes[i].turma}</a></td>
<td class="coluna_centralizada"><a href="../../supervisores/exibir/listar.php?instituicao_id={$instituicoes[i].id}">{$instituicoes[i].q_supervisores}</a></td>
<td>{$instituicoes[i].endereco}</td>
<td>{$instituicoes[i].telefone}</td>
</tr>
{/section}
</tbody>
</table>

{literal}
<script src="../../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#instituicoes').DataTable({
		language: { url: '../../libjs/datatables/pt-BR.json' },
		pageLength: 25,
		lengthMenu: [10, 25, 50, 100],
		order: []
	});
});
</script>
{/literal}
{/if}

</body>
</html>