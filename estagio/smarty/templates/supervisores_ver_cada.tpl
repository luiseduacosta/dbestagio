<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="pt-br">
<head>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <title>Supervisores</title>
  <meta content="author" name="Luis Acosta" >
  
  <style type="text/css">
  @import url("../../estagio.css");
  </style>

{literal}
<script type="text/javascript">

function get_periodo() {
	var periodo = document.getElementById('periodo').value;
	var id_periodo = document.getElementById('id_periodo').value;
	window.location = 'ver_cada.php?periodo=' + periodo + '&indice=0';
}

</script>
{/literal}

</head>

<body>


<select name='periodo' id='periodo' size=1 onChange='get_periodo();'>
{if !$periodo}
	<option value='0'>Seleciona perí­odo</option>
{else}
	<option value='0'>Período: {$periodo}</option>
{/if}
{section name=i loop=$periodos}
<option value='{$periodos[i]}'>{$periodos[i]}</option>
{/section}
</select>


<div align="center">
<table id='navegacao'>
<caption>Supervisores</caption>
<tbody>
<tr>

<td>
<form action=? method="POST">
<input type="hidden" name=indice value=0>
<input type="hidden" name=periodo id=id_periodo value='{$periodo}'>
<input type="submit" name=submit value="Primeiro">
</form>
</td>

<td>
<form action=? method="POST">
<input type="hidden" name=indice value={$indice+10}>
<input type="hidden" name=periodo id=id_periodo value='{$periodo}'>
<input type="submit" name=submit value="- 10">
</form>
</td>

<td>
<form action=? method="POST">
<input type="hidden" name=indice value={$indice-1}>
<input type="hidden" name=periodo id=id_periodo value='{$periodo}'>
<input type="submit" name=submit value="Retrocede">
</form>
</td>

<td>
<form action=? method="POST">
<input type="hidden" name=indice value={$indice+1}>
<input type="hidden" name=periodo id=id_periodo value='{$periodo}'>
<input type="submit" name=submit value="Avança">
</form>
</td>

<td>
<form action=? method="POST">
<input type="hidden" name=indice value={$indice+10}>
<input type="hidden" name=periodo id=id_periodo value='{$periodo}'>
<input type="submit" name=submit value="+ 10">
</form>
</td>

<td>
<form action=? method="POST">
<input type="hidden" name=indice value={$ultimo}>
<input type="hidden" name=periodo id=id_periodo value='{$periodo}'>
<input type="submit" name=submit value="Último">
</form>
</td>

{if $sistema_autentica == 1}
	<td style="background-color:red">
	<form action="../cancelar/cancela.php" method="POST">
	<input type="hidden" name=id_supervisor value={$id_supervisor}>
	<input type="hidden" name=indice value={$indice}>
	<input type="submit" name=submit value="Excluir">
	</form>
	</td>
{/if}

</tr>
</tbody>
</table>

<table border="1" width="95%">
<tbody>

<tr><th width="20%">Id</th><td>{$indice}</td></tr>
<tr><td>Cress</td><td>{$cress}</td></tr>
<tr><td>Nome</td><td>{$nome}</td></tr>
{* Usuarios nao cadastrados nao podem ver estes campos *}
{if $sistema_autentica == 1}
	<tr><td>Endereço</td><td>{$endereco}</td></tr>
	<tr><td>Bairro</td><td>{$bairro}</td></tr>
	<tr><td>CEP</td><td>{$cep}</td></tr>
	<tr><td>Município</td><td>{$municipio}</td></tr>
    <tr><td>Telefone</td><td>({$codigo_tel}){$telefone}</td></tr>
    <tr><td>Celular</td><td>({$codigo_cel}){$celular}</td></tr>
{/if}
<tr><td>E-mail</td><td>{$email}</td></tr>
<tr><td>Curso</td><td><a href='../../curso/ver_cada_supervisor.php?id_supervisor={$id_curso}'>{$id_curso}</a></td></tr>

{section name=i loop=$emprego}
<tr>
	<td>Instituição</td>
	<td>
		<a href="../../instituicoes/exibir/ver_cada.php?id_instituicao={$emprego[i].id_instituicao}">{$emprego[i].instituicao}</a>
	</td>
</tr>
{/section}

{* Usuarios nao cadastrados nao podem ver estes campos *}
{if $sistema_autentica == 1}
    <tr><td>Observações</td><td>{$observacoes}</td></tr>
{/if}

</tbody>
</table>

{* Usuarios nao cadastrados nao podem ver estes campos *}
{if $sistema_autentica == 1}
<table>
<tbody>
<tr>
<td style="text-align: center">
<form action="../inserir/auto_cadastro.php" method="post">
<input type="hidden" name="id_supervisor" value="{$id_supervisor}">
<input type="hidden" name="nome" value="{$nome}">
<input type="hidden" name="cress" value="{$cress}">
<input type="hidden" name="telefone" value="{$telefone}">
<input type="hidden" name="celular" value="{$celular}">
<input type="hidden" name="email" value="{$email}">
<input type="submit" name="submit" value="Modifica {$id_supervisor}">
</form>
</td>
</tr>
</tbody>
</table>

<table>
<tr>
	<td>
		<form name='sel_instituicao' action='#' method='post'>
			<select name='num_instituicao'>
				{section name=i loop=$instituicoes}
				<option value='{$instituicoes[i].id_instituicao}'>{$instituicoes[i].instituicao|truncate:50}</option>
				{/section}
			</select>
			<input type='hidden' name='id_supervisor' value='{$id_supervisor}'>
			<input type='submit' name='submit' value='Acrescentar instituição'>
		</form>
	</td>
</tr>
</table>
{/if}

{* Alunos *}
{if $alunos}
<table border="1">
<caption>Alunos</caption>
<tbody>
<tr>
<th>Registro</th>
<th>Nome</th>
<th>Instituição</th>
<th>Perí­odo</th>
</tr>
{section name=id loop=$alunos}
<tr>
<td>{$alunos[id].registro}</td>
<td><a href="../../alunos/exibir/ver_cada.php?id_aluno={$alunos[id].id_aluno}">{$alunos[id].nome}</a></td>
<td><a href="../../instituicoes/exibir/ver_cada.php?id_instituicao={$alunos[id].id_instituicao}">{$alunos[id].instituicao}</a></td>
<td style="text-align:center">{$alunos[id].periodo}</td>
</tr>
{/section}
</tbody>
</table>
{/if}

</div>

</body>
</html>