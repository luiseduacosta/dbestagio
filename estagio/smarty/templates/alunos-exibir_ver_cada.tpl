<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="pt-br">
<head>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <title>Ver cada aluno</title>
  <meta content="author" name="Luis Acosta">
<style type="text/css">
@import url("../../estagio.css");
</style>

{literal}
<script type="text/javascript" src="../../lib/jquery.js"></script>
<script type="text/javascript" src="../../lib/jquery.maskedinput-1.2.1.pack.js"></script>
<script type="text/javascript">
$(function() {
	$("#telefone").mask("9999.9999");
 	$("#celular").mask("9999.9999");
	$("#cep").mask("99999-999");
	$("#cpf").mask("999999999-99");	
});
</script>

<script type="text/javascript">

function get_periodo() {
	var periodo = document.getElementById('periodo').value;
	var id_periodo = document.getElementById('id_periodo').value;
	window.location = 'ver_cada.php?periodo=' + periodo + '&indice=0';
}

</script>

{/literal}

</head>

<body style="direction: ltr;">

{include file='cabecalho.tpl'}

<select name='periodo' id='periodo' size=1 onChange='get_periodo();'>
{if !$periodo}
	<option value='0'>Seleciona periodo</option>
{else}
	<option value='0'>Período: {$periodo}</option>
{/if}
{section name=i loop=$periodos}
<option value='{$periodos[i]}'>{$periodos[i]}</option>
{/section}
</select>

<!-- ###CORPO### -->

<div align="center">

<table id="navegacao">
<caption>Alunos</caption>
<tbody>
<tr>

<td>
<form action="#" method="post">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="botao" value="primeiro">
<input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
<input type="hidden" name="id_aluno" value="">
<input type="submit" name="submit" value="Primeiro">
</form>
</td>

<td>
<form action="#" method="post">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="botao" value="menos_10">
<input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
<input type="hidden" name="id_aluno" value="">
<input type="submit" name="submit" value=" - 10 ">
</form>
</td>

<td>
<form action="#" method="post">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="botao" value="retroceder">
<input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
<input type="hidden" name="id_aluno" value="">
<input type="submit" name="submit" value="Retroceder">
</form>
</td>

<td>
<form action="#" method="post">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="botao" value="avancar">
<input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
<input type="hidden" name="id_aluno" value="">
<input type="submit" name="submit" value="Avançar">
</form>
</td>

<td>
<form action="#" method="post">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="botao" value="mais_10">
<input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
<input type="hidden" name="id_aluno" value="">
<input type="submit" name="submit" value=" + 10 ">
</form>
</td>

<td>
<form action="#" method="post">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="botao" value="ultimo">
<input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
<input type="hidden" name="id_aluno" value="">
<input type="submit" name="submit" value="Último">
</form>
</td>

</tr>
</tbody>
</table>
</div>

<div align="center">
<table border="1" width="98%">
<tbody>

<tr>
<th width='80%'>Aluno estagiário {* $num_aluno *}</th>
{if $logado == 1}
	<th width='20%'><a href='../cancelar/ver_cancela.php?id_aluno={$num_aluno}'>Excluir registro</a></th>
{/if}
</tr>
</tbody>
</table>
</div>

<div align="center">
<table border="1" width="98%">
<tbody>

<tr>
<td width='20%'>Registro:</td>
<td width='80%'>{$registro}</td>
</tr>

<tr>
<td>Ingresso:</td>
{if !$periodo_intro}
<td>s/d</td>
{else}
<td>{$periodo_intro}&nbsp; - Perí­odo atual: {$tempo_cursado}o.</td>
{/if}
</tr>

<tr>
<td>Nome:</td>
<td>{$nome}</td>
</tr>

<tr>
<td>E-mail</td>
<td>{$email}</td>
</tr>

{if $logado == 1}
    <tr>
    <td>Telefone</td>
    <td>({$codigo_telefone}){$telefone}</td>
    </tr>

    <tr>
    <td>Celular</td>
    <td>({$codigo_celular}){$celular}</td>
    </tr>

    <tr>
    <td>Observa&ccedil;&otilde;es</td>
    <td>{$observacoes}</td>
    </tr>
{/if}

</tbody>
</table>
</div>

<div align="center">
<table border="1" width="98%">
<tbody>

<tr>
{if $logado == 1}
	<th>Editar</th>
{/if}
<th>Período</th>
<th>TC</th>
<th>Nível</th>
<th>Turno</th>
<th>Instituição</th>
<th>Supervisor</th>
<th>Professor</th>
{if $logado == 1}
    <th>Nota</th>
    <th>ch</th>
{/if}
</tr>

{section name=estagio loop=$historico_estagio}

<tr>
{if $logado == 1}
	<td><a href="../atualizar/atualiza_estagio.php?id_estagiarios={$historico_estagio[estagio].id_estagiario}&id_aluno={$num_aluno}">Editar</a></td>
{/if}
<td style="text-align:center">{$historico_estagio[estagio].periodo}</td>
<td style="text-align:center">{$historico_estagio[estagio].tc}</td>
<td style="text-align:center">{$historico_estagio[estagio].nivel}</td>
<td style="text-align:center">{$historico_estagio[estagio].turno}</td>
<td><a href="../../instituicoes/exibir/ver_cada.php?id_instituicao={$historico_estagio[estagio].id_instituicao}">{$historico_estagio[estagio].instituicao}</a></td>
{if $historico_estagio[estagio].id_supervisor eq 0}
	<td>&nbsp;</td>
{else}
	<td><a href="../../assistentes/exibir/ver_cada.php?id_supervisor={$historico_estagio[estagio].id_supervisor}">{$historico_estagio[estagio].supervisor}</a></td>
{/if}
<td>{$historico_estagio[estagio].professor}</td>
{if $logado == 1}
    <td style="text-align:center">{$historico_estagio[estagio].nota}</td>
    <td style="text-align:center">{$historico_estagio[estagio].ch}</td>
{/if}
</tr>

{/section}


{if $logado == 1}
    <tr>
    <td colspan="8" style="text-align: center">
    <form action="../atualizar/atualiza.php" method="post">
    <input type="hidden" name="id_aluno" value="{$num_aluno}">
    <input type="hidden" name="origem" value="{$origem}">
    <input type="submit" name="submit" value="Clique aqui para modificar dados do aluno ou atualizar/inserir est&aacute;gios">
    </form>
	</td>
    </tr>
{/if}

</tbody>
</table>
</div>

{* Monografia de fin do curso *}
{if $tcc}
    <table border='1'>
    <caption>Monografia de fim de curso</caption>
    <tbody>

    <tr>
    <th>Peri&oacute;do</th>
    <th>T&iacute;tulo</th>
    <th>Cat&aacute;logo</th>
    <th>Professor</th>
    </tr>

    <tr>
    <td style="text-align: center;">{$tcc.periodo}</td>
	<td><a href="../../../tcc/monografia/visualizar/ver_monografia.php?codigo={$tcc.id}">{$tcc.titulo}</a></td>
    <td style="text-align: center;">{$tcc.catalogo}</td>
    <td>{$tcc.professor}</td>
    </tr>

    </tbody>
    </table>
{/if}

<!-- ###CORPO### -->

{include file='rodape.tpl'}

</body>
</html>