<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="pt-br">
<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
  <title>Insere aluno</title>
  <meta content="Luis Acosta" name="author">
<style type="text/css">
@import url("../../estagio.css");
</style>
{literal}
<script language="JavaScript" type="text/javascript" src="../../lib/jquery.maskedinput-1.2.1.pack.js"></script>
<script language="JavaScript" type="text/javascript">
$(function() {
	$("#telefone").mask("9999.9999");
 	$("#celular").mask("9999.9999");
	$("#cep").mask("99999-999");
	$("#cpf").mask("999999999-99");	
});
</script>
{/literal}

{* Calendario *}
<link rel="stylesheet" type="text/css" href="../../../epoch/epoch_styles.css" />
{literal}
<script type="text/javascript" src="../../../epoch/epoch_classes.js"></script>
<script language="JavaScript" type="text/javascript">
var calendar1;
window.onload = function() {
	calendar1 = new Epoch('nascimento', 'popup', document.getElementById('nascimento'),false);
}
</script>
{/literal}

</head>

<body style="direction: ltr;">

<!-- 
<p>Cadastro: {$cadastro}</p>
-->

<div align="center" id="formulario_insere_aluno" style="visibility: visible">

{if $cadastro == 1}
    <form method="post" action="../atualizar/atualiza.php" name="modifica_aluno" id="modifica_aluno">
{else}
    <form method="post" action="inserir.php" name="inserir_aluno" id="inserir_aluno">
{/if}

<table border="1">
{if $cadastro == 1}
    <caption>Atualizar aluno j&aacute; cadastrado</caption>
{else}
    <caption>Inserir aluno novo</caption>
{/if}
<tbody>

<tr>
<td>Registro:</td>
<td><input type="text" maxlength="9" size="9" name="registro" value={$registro}></td>
</tr>

<tr>
<td>Nome:</td>
<td><input type="text" maxlength="50" size="50" name="nome" value='{$nome}'></td>
</tr>

<tr>
<td>Telefone</td>
<td>
<input type="text" maxlength="2" size="2" name="codigo_telefone" value={$codigo_telefone}>
<input type="text" maxlength="9" size="9" id="telefone" name="telefone" value={$telefone}>
</td>
</tr>

<tr>
<td>Celular</td>
<td>
<input type="text" maxlength="2" size="2" name="codigo_celular" value={$codigo_celular}>
<input type="text" maxlength="9" size="9" id="celular" name="celular" value={$celular}></td>
</tr>

<tr>
<td>E-mail</td>
<td><input type="text" maxlength="50" size="50" id="email" name="email" value='{$email}'></td>
</tr>

<tr>
<td>CPF</td>
<td>
<input type="text" maxlength="12" size="12" id="cpf" name="cpf" value='{$cpf}' />
</td>
</tr>

<tr>
<td>
Carteira de identidade: 
</td>
<td>
<input type="text" maxlength="15" size="15" id="identidade" name="identidade" value='{$identidade}' />
Orgão: 
<input type="text" maxlength="10" size="10" id="orgao" name="orgao" value='{$orgao}' />
</td>
</tr>

<tr>
<td>Data de nascimento</td>
<td>
<input id="nascimento" type="text" maxlength="10" size="10" name="nascimento" value='{$nascimento}' />
dd/mm/aaaa
</td>
</tr>

<tr>
<td>Endereço</td>
<td>
<input type="text" maxlength="50" size="30" id="endereco" name="endereco" value='{$endereco}' />
CEP: <input type="text" maxlength="9" size="9" id="cep" name="cep" value='{$cep}' />
</td>
</tr>

<tr>
<td>Município</td>
<td>
<input type="text" maxlength="30" size="20" name="municipio" value='{$municipio}' />
Bairro:
<input type="text" maxlength="30" size="20" name="bairro" value='{$bairro}' />
</td>
</tr>

<tr>
<td colspan="2" class="coluna_centralizada">
{if $cadastro == 1}
    <input type="hidden" name="id_aluno" value={$id_aluno}>
    <input type="hidden" name="origem" value={$origem}>
    <input type="hidden" name="valorcadastro" value={$cadastro}>
    <input type="submit" name="atualiza" value="Clique aqui para atualizar">
{else}
    <input type="hidden" name="origem" value={$origem}>
    <input type="hidden" name="valorcadastro" value={$cadastro}>
    <input type="submit" name="insere" value="Clique aqui para inserir">
{/if}
</td>
</tr>

</tbody>
</table>

</form>

</div>

</body>
</html>
