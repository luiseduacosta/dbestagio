<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="pt-br">
<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
  <title>Supervisores - {$nome}</title>
  <meta content="author" name="Luis Acosta">
  
  <link href="../estagio.css" rel="stylesheet" type="text/css">

{literal}
<script type="text/javascript">
function get_periodo() {
	var periodo = document.getElementById('periodo').value;
	window.location = 'ver_cada.php?periodo=' + encodeURIComponent(periodo) + '&indice=0';
}
</script>
{/literal}

</head>

<body>

<a href="listar.php">Voltar para a listagem</a><br>

<div align="center">
	<select name='periodo' id='periodo' size=1 onChange='get_periodo();'>
	{if !$periodo}
		<option value='0'>Seleciona período (Todos)</option>
	{else}
		<option value='0'>Período: {$periodo}</option>
	{/if}
	{foreach from=$periodos item=p}
	<option value='{$p}'>{$p}</option>
	{/foreach}
	</select>
</div>
<br>

<div align="center">
<table id='navegacao'>
<caption>Navegação de Supervisores</caption>
<tbody>
<tr>

<td>
<form action="ver_cada.php" method="POST">
<input type="hidden" name="indice" value="0">
<input type="hidden" name="periodo" value="{$periodo}">
<input type="submit" value="Primeiro">
</form>
</td>

<td>
<form action="ver_cada.php" method="POST">
<input type="hidden" name="indice" value="{$indice-10}">
<input type="hidden" name="periodo" value="{$periodo}">
<input type="submit" value="- 10">
</form>
</td>

<td>
<form action="ver_cada.php" method="POST">
<input type="hidden" name="indice" value="{$indice-1}">
<input type="hidden" name="periodo" value="{$periodo}">
<input type="submit" value="Retrocede">
</form>
</td>

<td>
<form action="ver_cada.php" method="POST">
<input type="hidden" name="indice" value="{$indice+1}">
<input type="hidden" name="periodo" value="{$periodo}">
<input type="submit" value="Avança">
</form>
</td>

<td>
<form action="ver_cada.php" method="POST">
<input type="hidden" name="indice" value="{$indice+10}">
<input type="hidden" name="periodo" value="{$periodo}">
<input type="submit" value="+ 10">
</form>
</td>

<td>
<form action="ver_cada.php" method="POST">
<input type="hidden" name="indice" value="{$ultimo}">
<input type="hidden" name="periodo" value="{$periodo}">
<input type="submit" value="Último">
</form>
</td>

<td>
<form action="inserir.php" method="GET">
<input type="submit" value="Inserir">
</form>
</td>

<td>
<form action="atualizar.php" method="GET">
<input type="hidden" name="supervisor_id" value="{$supervisor_id}">
<input type="submit" value="Modificar">
</form>
</td>

<td style="background-color:red">
<form action="excluir.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este supervisor?');">
<input type="hidden" name="supervisor_id" value="{$supervisor_id}">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" value="Excluir">
</form>
</td>

</tr>
</tbody>
</table>
</div>

<div align="center">
<h3>Supervisor: {$nome}</h3>

<table border="1" width="95%">
<tbody>

<tr><th width="20%">Id</th><td>{$supervisor_id}</td></tr>
<tr><td>Cress</td><td>{$cress}</td></tr>
<tr><td>Nome</td><td>{$nome}</td></tr>
<tr><td>CPF</td><td>{$cpf}</td></tr>
<tr><td>Telefone</td><td>{if $telefone}({$codigo_telefone}) {$telefone}{else}—{/if}</td></tr>
<tr><td>Celular</td><td>{if $celular}({$codigo_celular}) {$celular}{else}—{/if}</td></tr>
<tr><td>E-mail</td><td>{if $email}<a href="mailto:{$email}">{$email}</a>{else}—{/if}</td></tr>
<tr><td>Endereço</td><td>{$endereco}</td></tr>
<tr><td>Bairro</td><td>{$bairro}</td></tr>
<tr><td>Município</td><td>{$municipio}</td></tr>
<tr><td>CEP</td><td>{$cep}</td></tr>
<tr><td>Região (CRESS)</td><td>{$regiao}</td></tr>
<tr><td>Escola</td><td>{$escola}</td></tr>
<tr><td>Ano de formação</td><td>{$ano_formacao}</td></tr>
<tr><td>Cargo</td><td>{$cargo}</td></tr>

<tr>
<td>Curso</td>
<td>
{if $id_curso}
    <a href="../curso/ver_cada_supervisor.php?id_supervisor={$id_curso}">{$id_curso}</a>
    &nbsp;<a href="../curso/atualiza_supervisor_estagio.php?id_supervisor_curso={$id_curso}&indice={$indice}">[Sincronizar]</a>
{else}
    —
{/if}
</td>
</tr>

{foreach from=$emprego item=emp}
<tr>
	<td>Instituição vinculada</td>
	<td>
		<a href="../instituicoes/ver_cada.php?instituicao_id={$emp.id}">{$emp.instituicao}</a>
		&nbsp;<a href="inst_supervisor.php?supervisor_id={$supervisor_id}&instituicao_id={$emp.id}" onclick="return confirm('Desvincular esta instituição?');">[Desvincular]</a>
	</td>
</tr>
{/foreach}

<tr><td>Observações</td><td>{$observacoes}</td></tr>

</tbody>
</table>

<br>
<table>
<tr>
	<td>
		<form name="sel_instituicao" action="ver_cada.php" method="POST">
			<select name="num_instituicao">
				<option value="0">Selecione uma instituição para vincular</option>
				{foreach from=$instituicoes item=inst}
				<option value="{$inst.id}">{$inst.instituicao|truncate:50}</option>
				{/foreach}
			</select>
			<input type="hidden" name="supervisor_id" value="{$supervisor_id}">
			<input type="hidden" name="indice" value="{$indice}">
			<input type="submit" value="Acrescentar instituição">
		</form>
	</td>
</tr>
</table>

{if $alunos}
<br>
<table border="1" width="95%">
<caption>Alunos Supervisionados</caption>
<thead>
<tr>
<th>Registro</th>
<th>Nome</th>
<th>Instituição</th>
<th>Período</th>
</tr>
</thead>
<tbody>
{foreach from=$alunos item=al}
<tr>
<td class="coluna_direita">{$al.registro}</td>
<td><a href="../alunos/ver_cada.php?aluno_id={$al.aluno_id}">{$al.nome}</a></td>
<td><a href="../instituicoes/ver_cada.php?instituicao_id={$al.instituicao_id}">{$al.instituicao}</a></td>
<td style="text-align:center">{$al.periodo}</td>
</tr>
{/foreach}
</tbody>
</table>
{/if}

</div>

</body>
</html>
