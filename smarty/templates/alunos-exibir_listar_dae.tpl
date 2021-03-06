<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="pt-br">
<head>

  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
  <title>Alunos</title>
  <meta content="author" name="Luis Acosta" >
  
  <style type="text/css">
  @import url("../../estagio.css");
  </style>

{literal}
<script type="text/javascript">
function carrega_tabela() {
	turma=document.getElementById('turma').value;
	// alert(turma);
	window.location="listar_dae.php?turma=" + turma;
	return false;
}
</script>
{/literal}
 
</head>

<body style="direction: ltr;">

<select name='turma' id='turma' onChange="return carrega_tabela();">
<option value='{$turma}'>Período: {$turma}</option>
<option value='0'>Todos</option>
{section name=i loop=$periodos}
<option value='{$periodos[i]}'>{$periodos[i]}</option>
{/section}
</select>

<div class="corpo">

<!--
Tabela principal
//-->

<div class="centro">
<table border="1">
<caption>Lista de alunos em est&aacute;gio para DAE</caption>
  <tbody>
    <tr>
    	<th>Id</th>
        <th><a href="?ordem=registro">Registro</a></th>
        <th><a href="?ordem=nivel">Nivel</a></th>
        <th><a href="?ordem=nome">Nome</a></th>
        <th><a href="?ordem=endereco">Endereço</a></th>
        <th><a href="?ordem=bairro">Bairro</a></th>
        <th><a href="?ordem=municipio">Munic&iacute;pio</a></th>
        <th><a href="?ordem=cep">CEP</a></th>
        <th><a href="?ordem=identidade">RG</a></th>
        <th><a href="?ordem=orgao">Orgão</a></th>
        <th><a href="?ordem=nascimento">Data nascimento</a></th>
        <th><a href="?ordem=cpf">CPF</a></th>
        <th><a href="?ordem=codigo_telefone">Código</a></th>
        <th><a href="?ordem=telefone">Telefone</a></th>
        <th><a href="?ordem=codigo_celular">Código</a></th>
        <th><a href="?ordem=celular">Celular</a></th>
        <th><a href="?ordem=email">E-mail</a></th>
        <th><a href="?ordem=instituicao">Instituição</a></th>
        <th><a href="?ordem=seguro">Seguro</a></th>
    </tr>
    {assign var = "i" value = 1}
    {section name=i loop=$dae}
    <tr>
	<td style="text-align:right">{$i++}</td>
	<td style="text-align:center">{$dae[i].registro}</td>
	<td style="text-align:center">{$dae[i].nivel}</td>
        <td><a href="ver_cada.php?id_aluno={$dae[i].registro}">{$dae[i].nome}</a></td>
	<td>{$dae[i].endereco}</td>
	<td>{$dae[i].bairro}</td>
	<td>{$dae[i].municipio}</td>
	<td style="text-align:center">{$dae[i].cep}</td>
	<td style="text-align:center">{$dae[i].identidade}</td>
	<td style="text-align:center">{$dae[i].orgao}</td>  
	<td style="text-align:center">{$dae[i].nascimento}</td>  
	<td style="text-align:center">{$dae[i].cpf}</td>
	<td style="text-align:right">{$dae[i].codigo_telefone}</td>
	<td style="text-align:right">{$dae[i].telefone}</td>
	<td style="text-align:right">{$dae[i].codigo_celular}</td>
	<td style="text-align:right">{$dae[i].celular}</td>	
	<td>{$dae[i].email}</td>
	<td><a href="../../instituicoes/exibir/ver_cada.php?id_instituicao={$dae[i].id_instituicao}">{$dae[i].instituicao}</a></td>
	{if $dae[i].seguro eq 0}
		<td style="text-align:left">Instituição</td>
	{else}
		<td style="text-align:left">UFRJ</td>
	{/if}
    </tr>
    {/section}
  </tbody>
</table>
</div>

</div>

</body>
</html>