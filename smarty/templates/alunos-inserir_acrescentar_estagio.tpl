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
<script language="JavaScript" type="text/javascript">
function verificaPeriodo()
{
    var tamanho = document.inserir_aluno.periodo.value.length;
    return;
}

function aluno()
{
    var id_aluno = document.form_estagiarios.id_aluno.value;
    // alert("Id aluno = " + id_aluno);
    window.location="../atualizar/atualiza.php?id_aluno=" + id_aluno;
}
</script>
{/literal}

</head>

<body style="direction: ltr;">

<div align="center" id="formulario_acrescenta_estagio" style="visibility: visible">
<p style="background-color:#e7e1ae; text-align:center; font-weight:bold; font-size:14px">Inserir est�gio do aluno <a href="../exibir/ver_cada.php?id_aluno={$id_aluno}&origem=seleciona">{$aluno_nome}</a> ({$registro})</p>
</div>

<div align="center" id="historico_estagios" style="visibility: visible">
<table border="1">
<tr>
<th>Período</th>
<th>TC</th>
<th>Estágio</th>
<th>Turno</th>
<th>ch</th>
<th>Nota</th>
<th>Instituição</th>
<th>Modificar</th>
<th>Excluir</th>
</tr>

{section name=elemento loop=$estagiarios}
<tr>
<td style="text-align:center">{$estagiarios[elemento].periodo}</td>
<td style="text-align:center">{$estagiarios[elemento].tc}</td>
<td style="text-align:center">{$estagiarios[elemento].nivel}</td>
<td style="text-align:center">{$estagiarios[elemento].turno}</td>
<td style="text-align:center">{$estagiarios[elemento].ch}</td>
<td style="text-align:center">{$estagiarios[elemento].nota}</td>
<td>{$estagiarios[elemento].instituicao}</td>
<td><a href="../atualizar/atualiza_estagio.php?id_estagiarios={$estagiarios[elemento].id}&id_aluno={$id_aluno}">Modificar</a></td>
<td><a href="../cancelar/cancela_estagio.php?id_estagiarios={$estagiarios[elemento].id}&id_aluno={$id_aluno}">Excluirr</a></td>
</tr>
{/section}

</table>
</div>

<div style="width: 600px; height: 30px; color: green; visibility: visible">
</div>

<div align="center" class="acrescentar_estagiario" id="acrescentar_estagiario" style="visibility: visible">

<form name="form_acrescentar_estagiarios" id="form_acrescentar_estagiarios" action="acrescentar_estagio.php" method="post">

<table border="1" width="80%">
<caption>Inserir estágio</caption>
<tbody>

<tr>
<td>Período</td>
<td>
<input type="text" name="periodo" id="periodo" size="6" maxlength="6" onChange="return verificaPeriodo();">
Formato: AAAA-1 ou 2
</td>
</tr>

<tr>
<td>Termo compromisso</td>
<td>
Sim <input type="radio" name="tc" id="tc" value="1">
Nao <input type="radio" name="tc" id="tc" value="0" checked>
</td>
</tr>

<tr>
<td>Nível:</td>
<td>
Estágio I   <input type="radio" name="nivel" value="1">
Estágio II  <input type="radio" name="nivel" value="2">
Estágio III <input type="radio" name="nivel" value="3">
Estágio IV  <input type="radio" name="nivel" value="4">
</td>
</tr>

<tr>
<td>Turno:</td>
<td>
Diurno <input type="radio" name="turno" value="D">
Noturno <input type="radio" name="turno" value="N">
</td>
</tr>

<tr>
<td>Avaliação</td>
<td>
Nota (decimal): <input type="text" name="nota" id="nota" size="5" maxlength="5" value="0,00">
Carga horaria (inteiro): <input type="text" name="ch" id="ch" size="5" maxlength="5" value="0">
</td>
</tr>

<tr>
<td>Instituição:</td>
<td>
<select name="id_instituicao" size="1">
<option value="0">Selecione instituição</option>
{section name=elemento loop=$instituicoes}
<option value="{$instituicoes[elemento].id_instituicao}">
{$instituicoes[elemento].instituicao|truncate:50}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td>Supervisor:</td>
<td>
<select name="id_supervisor" size="1">
<option value="0">Selecione supervisor</option>
{section name=elemento loop=$supervisores}
<option value="{$supervisores[elemento].id_supervisor}">
{$supervisores[elemento].supervisor|truncate:50}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td>Professor:</td>
<td>
<select name="id_professor" size="1">
<option value="0">Selecione professor</option>
{section name=elemento loop=$professores}
<option value="{$professores[elemento].id_professor}">
{$professores[elemento].professor|truncate:50}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td>Área:</td>
<td>
<select name="id_area" size="1">
<option value="0">Selecione área</option>
{section name=elemento loop=$areas}
<option value="{$areas[elemento].id_area}">
{$areas[elemento].area|truncate:50}</option>
{/section}
</select>
</td>
</tr>

<input type="hidden" name="id_aluno" id="id_aluno" value="{$id_aluno}">
<input type="hidden" name="nome" id="nome" value="{$aluno_nome}">
<input type="hidden" name="registro" id="registro" value="{$registro}">

<tr>
<td colspan="2" class="coluna_centralizada">
<input type="submit" name="submit" value="Confirma">
</td>
</tr>

</tbody>
</table>

</form>

</div>

</body>
</html>
