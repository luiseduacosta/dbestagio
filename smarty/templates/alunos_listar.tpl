<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Alunos</title>

{literal}
<script type="text/javascript">
function carrega_tabela() {
	periodo = document.getElementById('periodo').value;
	instituicao_id = document.getElementById('instituicao_id').value;
	window.location = "listar.php?periodo=" + periodo + "&instituicao_id=" + instituicao_id;
	return false;
}
</script>
{/literal}

</head>
<body>

<a href="javascript:history.back();">Voltar</a><br>

<div align="center">
<h3>Lista de Alunos</h3>
</div>

<table class="ficha">
<tr>
<td><label for="busca">Buscar (nome ou registro)</label></td>
<td><input type="text" id="busca" name="busca" size="30" onkeyup="if(event.key=='Enter')window.location='listar.php?busca='+this.value+'&periodo={$periodo}&instituicao_id={$instituicao_id}';">{$busca}</td>
</tr>
<tr>
<td><label for="periodo">Período</label></td>
<td>
<select name='periodo' id='periodo' onChange="return carrega_tabela();">
<option value=''>Todos os períodos</option>
{section name=i loop=$periodos}
<option value='{$periodos[i]}' {if $periodos[i] == $periodo}selected{/if}>{$periodos[i]}</option>
{/section}
</select>
</td>
</tr>
<tr>
<td><label for="instituicao_id">Instituição de estágio</label></td>
<td>
<select name='instituicao_id' id='instituicao_id' onChange="return carrega_tabela();">
<option value='0'>Todas as instituições</option>
{section name=i loop=$instituicoes}
<option value='{$instituicoes[i].id}' {if $instituicoes[i].id == $instituicao_id}selected{/if}>{$instituicoes[i].instituicao}</option>
{/section}
</select>
</td>
</tr>
</table>

<table id="alunos" class="display">
<thead>
<tr>
<th>Registro</th>
<th>Nome</th>
<th>Ingresso</th>
<th>Períodos de estágio</th>
<th>Nº estágios</th>
{if $smarty.cookies.usuario}
<th>Celular</th>
<th>E-mail</th>
<th>Ações</th>
{/if}
</tr>
</thead>
<tbody>

{section name=i loop=$alunos}
<tr>
<td class="coluna_direita">{$alunos[i].registro}</td>
<td>
{if $smarty.cookies.usuario}
<a href="ver_cada.php?aluno_id={$alunos[i].id}">{$alunos[i].nome}</a>
{else}
{$alunos[i].nome}
{/if}
</td>
<td class="coluna_centralizada">{$alunos[i].ingresso}</td>
<td>{$alunos[i].periodos_estagio}</td>
<td class="coluna_centralizada">{$alunos[i].num_estagios}</td>
{if $smarty.cookies.usuario}
<td>{$alunos[i].celular}</td>
<td>{$alunos[i].email}</td>
<td>
<a href="ver_cada.php?aluno_id={$alunos[i].id}">Ver</a> |
<a href="../atualizar/atualiza.php?aluno_id={$alunos[i].id}">Editar</a> |
<a href="../cancelar/cancela.php?aluno_id={$alunos[i].id}" onclick="return confirm('Excluir este aluno?');">Excluir</a>
</td>
{/if}
</tr>
{/section}

</tbody>
</table>

<div align="center">
<p><a href="../inserir/seleciona_aluno.php">Novo aluno</a></p>
</div>

{literal}
<script src="../../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#alunos').DataTable({
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