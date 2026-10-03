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

<div align="center">
<table id="navegacao" border="0">
<tbody>
<tr>

{if $primeiro_id}
<td>
<form action="ver_cada.php" method="get">
<input type="hidden" name="professor_id" value="{$primeiro_id}">
<input type="submit" value="Primeiro">
</form>
</td>
{/if}

{if $menos_10_id}
<td>
<form action="ver_cada.php" method="get">
<input type="hidden" name="professor_id" value="{$menos_10_id}">
<input type="submit" value="-10">
</form>
</td>
{/if}

{if $anterior_id}
<td>
<form action="ver_cada.php" method="get">
<input type="hidden" name="professor_id" value="{$anterior_id}">
<input type="submit" value="Retroceder">
</form>
</td>
{/if}

{if $proximo_id}
<td>
<form action="ver_cada.php" method="get">
<input type="hidden" name="professor_id" value="{$proximo_id}">
<input type="submit" value="Avançar">
</form>
</td>
{/if}

{if $mais_10_id}
<td>
<form action="ver_cada.php" method="get">
<input type="hidden" name="professor_id" value="{$mais_10_id}">
<input type="submit" value="+10">
</form>
</td>
{/if}

{if $ultimo_id}
<td>
<form action="ver_cada.php" method="get">
<input type="hidden" name="professor_id" value="{$ultimo_id}">
<input type="submit" value="Último">
</form>
</td>
{/if}

<td>
<form action="../inserir/form.php" method="get">
<input type="submit" value="Inserir">
</form>
</td>

<td>
<form action="../atualizar/atualiza.php" method="get">
<input type="hidden" name="professor_id" value="{$professor_id}">
<input type="submit" value="Modificar">
</form>
</td>

<td style="background-color:red">
<form action="../cancelar/cancela.php" method="get" onClick="return confirm('Tem certeza?');">
<input type="hidden" name="professor_id" value="{$professor_id}">
<input type="submit" value="Excluir">
</form>
</td>

</tr>
</tbody>
</table>
</div>

<a href="javascript:history.back();">Voltar</a>
<div align="center">
<h3>Professor: {$professor_nome}</h3>
<p>{$num_estagios} estágio(s) | {$num_instituicoes} instituição(ões)</p>
</div>

<div align="center">
<table border="1" width="60%">
<caption>Dados do professor</caption>
<tbody>

<tr><td width="30%">Id</td><td>{$professor_id}</td></tr>
<tr><td>Nome</td><td>{$professor_nome}</td></tr>
<tr><td>CPF</td><td>{$cpf}</td></tr>
<tr><td>SIAPE</td><td>{$siape}</td></tr>
<tr><td>Cress</td><td>{$cress}</td></tr>
<tr><td>Região (CRESS)</td><td>{$regiao}</td></tr>
<tr><td>Telefone</td><td>{$telefone}</td></tr>
<tr><td>Celular</td><td>{$celular}</td></tr>
<tr><td>E-mail</td><td>{$email}</td></tr>
<tr><td>Currículo Lattes</td><td>{$curriculolattes}</td></tr>
<tr><td>Atualização Lattes</td><td>{$atualizacaolattes}</td></tr>
<tr><td>Data de ingresso</td><td>{$dataingresso}</td></tr>
<tr><td>Tipo de cargo</td><td>{$tipocargo}</td></tr>
<tr><td>Departamento</td><td>{$departamento}</td></tr>
<tr><td>Data de egresso</td><td>{$dataegresso}</td></tr>
<tr><td>Motivo de egresso</td><td>{$motivoegresso}</td></tr>
<tr><td>Status</td><td>{$status}</td></tr>
<tr><td>Observações</td><td>{$observacoes}</td></tr>
<tr><td>Registrado em</td><td>{$created}</td></tr>
<tr><td>Alterado em</td><td>{$modified}</td></tr>

</tbody>
</table>
</div>

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
<td>
{if $e.instituicao_id}
<a href="../../instituicoes/exibir/ver_cada.php?instituicao_id={$e.instituicao_id}">{$e.instituicao}</a>
{else}
{$e.instituicao}
{/if}
</td>
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