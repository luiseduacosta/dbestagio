<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="pt-br">
<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
  <title>Aluno modifica estágio</title>
  <meta content="Luis Acosta" name="author">
<style type="text/css">
@import url("../../estagio.css");
</style>
{literal}
<script language="JavaScript" type="text/javascript">
function verificaPeriodo() {
    var tamanho = document.inserir_aluno.periodo.value.length;
    return;
}

function aluno() {
    var id_aluno = document.form_estagiarios.id_aluno.value;
    // alert("Id aluno = " + id_aluno);
    window.location="../atualizar/atualiza_dae.php?id_aluno=" + id_aluno;
}
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
Modifica dados do aluno
//-->

<div align="center" id="formulario_modifica_estagio" style="visibility: visible">

<table border="1" width="80%">
<caption>Termo de compromisso: {$aluno_nome} DRE: {$registro}</caption>
<tbody>

<tr>
<td>Registro</td>
<td>{$registro}</td>
</tr>

<tr>
<td>Nome</td>
<td>{$aluno_nome}</td>
</tr>

<tr>
<td>Telefone:</td>
<td>
({$codigo_telefone})
{$telefone}
Celular:
({$codigo_celular})
{$celular}
</td>
</tr>

<tr>
<td>E-mail:</td>
<td>{$email}</td>
</tr>

<tr>
<td>CPF</td>
<td>{$cpf}</td>
</tr>

<tr>
<td>Carteira de identidade:</td>
<td>{$identidade} Orgão: {$orgao}</td>
</tr>

<tr>
<td>Data de nascimento</td>
<td>
{$nascimento}
dd/mm/aaaa
</td>
</tr>

<tr>
<td>Endereço</td>
<td>{$endereco} CEP: {$cep}</td>
</tr>

<tr>
<td>Bairro</td>
<td>{$bairro} Municipio:{$municipio}
</td>
</tr>

<!--
<tr>
<td>Observa&ccedil;&otilde;es</td>
<td>
<p>
{$observacoes}
</p>
</td>
</tr>
//-->

<tr>
<td colspan="2" class="coluna_centralizada">
<form action="atualiza_dae.php" method="post" name="atualizar_dae">
<input type="hidden" name="registro" value={$registro}>
<input type="submit" name="modificar" value="Modificar">
</form>
</td>
</tr>

</tbody>
</table>

</div>

</body>
</html>
