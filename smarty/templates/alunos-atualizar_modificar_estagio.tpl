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

<!--
Modifica dados do aluno
//-->

<div align="center" id="formulario_modifica_estagio" style="visibility: visible">

<form action="atualiza.php" name="atualiza_aluno" id="atualiza_aluno" method="post">

<table border="1" width="80%">
<caption>Modificar dados do aluno <a href="../exibir/ver_cada.php?id_aluno={$id_aluno}&origem=seleciona">{$aluno_nome}</a> ({$registro})</caption>
<tbody>

<tr>
<td>Nome</td>
<td><input type="text" name="nome" size="30" maxlength="50" value="{$aluno_nome}"></td>
</tr>

<tr>
<td>Registro</td>
<td><input type="text" name="registro" size="10" maxlength="10" value="{$registro}"></td>
</tr>

<tr>
<td>Telefone:</td>
<td>
<input type="text" name="codigo_telefone" size="2" maxlength="2" value="{$codigo_telefone}">
<input type="text" name="telefone" size="9" maxlength="9" value="{$telefone}">
</td>
</tr>

<tr>
<td>Celular:</td>
<td>
<input type="text" name="codigo_celular" size="2" maxlength="2" value="{$codigo_celular}">
<input type="text" name="celular" size="9" maxlength="9" value="{$celular}">
</td>
</tr>

<tr>
<td>E-mail:</td>
<td><input type="text" name="email" size="30" maxlength="50" value="{$email}"></td>
</tr>

<tr>
<td>CPF</td>
<td>
<input type="text" maxlength="12" size="12" name="cpf" value='{$cpf}' />
Carteira de identidade: 
<input type="text" maxlength="15" size="15" name="identidade" value='{$identidade}' />
</td>
</tr>

<tr>
<td>Data de nascimento</td>
<td>
<input id="nascimento" type="text" maxlength="10" size="10" name="nascimento" value='{$nascimento}' />
</td>
</tr>

<tr>
<td>Endereço</td>
<td>
<input type="text" maxlength="50" size="30" name="endereco" value='{$endereco}' />
CEP: <input type="text" maxlength="9" size="9" name="cep" value='{$cep}' />
</td>
</tr>

<tr>
<td>Bairro</td>
<td>
<input type="text" maxlength="30" size="15" name="bairro" value='{$bairro}' />
Município:
<input type="text" maxlength="30" size="20" name="municipio" value='{$municipio}' />
</td>
</tr>

<tr>
<td colspan="2" style="text-align: center">
<input type="hidden" name="acao" value="1">
<input type="hidden" name="id_aluno" value="{$id_aluno}">
<input type="submit" name="submit" value="Confirma">
</td>
</tr>

</tbody>
</table>

</form>

</div>


<div align="center" style="with: 600px; height: 30px; color: green">
</div>

<!--
Modifica dados dos campos de estágio
//-->

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
</tr>

{section name=elemento loop=$estagiarios}
<tr>
<td>{$estagiarios[elemento].periodo}</td>
<td>{$estagiarios[elemento].tc}</td>
<td>{$estagiarios[elemento].nivel}</td>
<td>{$estagiarios[elemento].turno}</td>
<td>{$estagiarios[elemento].ch}</td>
<td>{$estagiarios[elemento].nota}</td>
<td>{$estagiarios[elemento].instituicao}</td>
<td><a href="../atualizar/atualiza_estagio.php?id_estagiarios={$estagiarios[elemento].id}&id_aluno={$id_aluno}">Modifica</a></td>
<td><a href="../cancelar/cancela_estagio.php?id_estagiarios={$estagiarios[elemento].id}&id_aluno={$id_aluno}">Canelar</a></td>
</tr>
{/section}
</table>

</div>

<div style="width: 600px; height: 30px; color: green; visibility: visible">
</div>

<!--
Inserir novos campos de estagio do aluno
//-->

<form name="form_insere_estagio" id="form_insere_estagio" action="../inserir/acrescentar_estagio.php" method="post">

<div align="center" class="modificar_estagiario" id="modificar_estagiario" style="visibility: visible">

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
<option value="0">Selecione area</option>
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

</div>

</form>

</body>
</html>
