<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="pt-br">
<head>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <title>Ver aluno antes de ser cancelado</title>
  <meta content="author" name="Luis Acosta">
<style type="text/css">
@import url("../../estagio.css");
</style>
{literal}
<script languaje="Javascript" type="text/javascript">
function verifica() {
   	/* var motivo = document.getElementById("motivo").value; */
    var motivo = document.erro.erro.value;
	if (motivo == "") {
		// alert("Motivo vazio" + motivo);
	} else {
		if (motivo == 0) {
			alert("Registro n�o foi excluido por estar relacionadao com est�gios. Exclua primeiramente os est�gios para logo poder excluir o aluno ");
			} else {
			// alert("Registro cancelado!! " + motivo);
			}
	}
	
}
function confirma() {
	var num_aluno;
	num_aluno = form_motivo.id_aluno.value;
	resposta=confirm("Tem certeza?");
	if(resposta) {
		return true;
	} else {
		alert("A��o abortada!");
		return false;
	}
}
</script>
{/literal}
</head>

<body onLoad="return verifica();">

<form name="erro" id="erro" action="#" method="get">
<input type="hidden" name="erro" id="erro" value={$erro}>
</form>

{if $nivel eq 4}
<p>Aluno de Est�gio IV</p>
{else}
<p>Aluna(o) {$aluno} ainda n�o finalizou est�gio</p>
{/if}

<div align="center">
<table border="1" width="98%">
<tbody>

<tr>
<td width="20%">{$id_aluno}</td>
<td widht="80%">Alunos estagi�rios</td>
</tr>

<tr>
<td>Registro:</td>
<td>{$registro}</td>
</tr>

<tr>
<td>Nome:</td>
<td>{$nome}</td>
</tr>

<tr>
<td>Nivel: </td>
<td>
{if $nivel eq "1"}
    Est�gio I   <input type="radio" name="nivel" value="1" checked>
    Est�gio II  <input type="radio" name="nivel" value="2">
    Est�gio III <input type="radio" name="nivel" value="3">
    Est�gio IV  <input type="radio" name="nivel" value="4">
{elseif $nivel eq "2"}
    Est�gio I   <input type="radio" name="nivel" value="1">
    Est�gio II  <input type="radio" name="nivel" value="2" checked>
    Est�gio III <input type="radio" name="nivel" value="3">
    Est�gio IV  <input type="radio" name="nivel" value="4">
{elseif $nivel eq "3"}
    Est�gio I   <input type="radio" name="nivel" value="1">
    Est�gio II  <input type="radio" name="nivel" value="2">
    Est�gio III <input type="radio" name="nivel" value="3" checked>
    Est�gio IV  <input type="radio" name="nivel" value="4">
{elseif $nivel eq "4"}
    Est�gio I   <input type="radio" name="nivel" value="1">
    Est�gio II  <input type="radio" name="nivel" value="2">
    Est�gio III <input type="radio" name="nivel" value="3">
    Est�gio IV  <input type="radio" name="nivel" value="4" checked>
{else}
    Sem informa��o
{/if}
</td>
</tr>

<tr>
<td>Turno</td>
<td>
{if $turno eq "D"}
    Diurno:  <input type="radio" name="turno" value="D" checked>
    Noturno: <input type="radio" name="turno" value="N">
{elseif $turno eq "N"}
    Diurno:  <input type="radio" name="turno" value="D">
    Noturno: <input type="radio" name="turno" value="N" checked>
{else}
    Sem informa��o
{/if}
</td>
</tr>

<tr>
<td>Institui��o:</td>
<td><a href="../../instituicoes/exibir/exibir.php?id_instituicao={$id_instituicao}">{$instituicao}</a></td>
</tr>

<tr>
<td>Supervisor:</td>
<td><a href="../../assistentes/exibir/exibir.php?id_supervisor={$id_supervisor}">{$supervisor}</a></td>
</tr>

</tbody>
</table>

<form name="form_motivo" id="form_motivo" method="post" action="cancela.php" onSubmit="return confirma();">
<input type="hidden" name="id_aluno" id="id_aluno" value="{$id_aluno}">
<tr>
<td colspan="2" style="text-align:center"><input type="submit"
name="submit" value="Confirma excluir"></td>
</tr>
</form>

</div>

</body>
</html>
