<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>{$titulo}</title>
</head>
<body>

<div align="center">
<h3>{$titulo}</h3>
</div>

<span style="font-size:85%;font-style:italic">Datas no formato AAAA-MM-DD.</span>

<form name="form_estagiario" action="{$acao}" method="post">

{if $e_edicao}
<input type="hidden" name="id" value="{$v.id}">
{/if}

<table class="ficha">
<tbody>

<tr>
<td class="coluna_direita"><label for="aluno_id">Aluno *</label></td>
<td>
<select id="aluno_id" name="aluno_id" size="1">
<option value="0">Selecione o aluno</option>
{foreach item=a from=$alunos}
<option value="{$a.id}" {if $a.id == $v.aluno_id}selected="selected"{/if}>{$a.nome} ({$a.registro})</option>
{/foreach}
</select>
</td>
</tr>

<tr>
<td class="coluna_direita"><label for="periodo">Período *</label></td>
<td><input type="text" id="periodo" name="periodo" size="8" maxlength="6" value="{$v.periodo}" placeholder="ex.: 2024-1"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="nivel">Nível *</label></td>
<td>
<select id="nivel" name="nivel" size="1">
{foreach item=n from=$niveis}
<option value="{$n}" {if $n == $v.nivel}selected="selected"{/if}>{if $n}Nível {$n}{else}—{/if}</option>
{/foreach}
</select>
</td>
</tr>

<tr>
<td class="coluna_direita"><label for="tc">TC</label></td>
<td>
<select id="tc" name="tc" size="1">
<option value="0" {if $v.tc == 0}selected="selected"{/if}>0</option>
<option value="1" {if $v.tc == 1}selected="selected"{/if}>1</option>
</select>
</td>
</tr>

<tr>
<td class="coluna_direita"><label for="tc_solicitacao">TC solicitado</label></td>
<td><input type="text" id="tc_solicitacao" name="tc_solicitacao" size="10" maxlength="10" value="{$v.tc_solicitacao}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="instituicao_id">Instituição *</label></td>
<td>
<select id="instituicao_id" name="instituicao_id" size="1">
<option value="0">Selecione</option>
{foreach item=i from=$instituicoes}
<option value="{$i.id}" {if $i.id == $v.instituicao_id}selected="selected"{/if}>{$i.instituicao}</option>
{/foreach}
</select>
</td>
</tr>

<tr>
<td class="coluna_direita"><label for="supervisor_id">Supervisor</label></td>
<td>
<select id="supervisor_id" name="supervisor_id" size="1">
<option value="0">—</option>
{foreach item=s from=$supervisores}
<option value="{$s.id}" {if $s.id == $v.supervisor_id}selected="selected"{/if}>{$s.nome}</option>
{/foreach}
</select>
</td>
</tr>

<tr>
<td class="coluna_direita"><label for="professor_id">Professor</label></td>
<td>
<select id="professor_id" name="professor_id" size="1">
<option value="0">—</option>
{foreach item=p from=$professores}
<option value="{$p.id}" {if $p.id == $v.professor_id}selected="selected"{/if}>{$p.nome}</option>
{/foreach}
</select>
</td>
</tr>

<tr>
<td class="coluna_direita"><label for="nota">Nota</label></td>
<td><input type="text" id="nota" name="nota" size="6" maxlength="5" value="{$v.nota}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="ch">Carga horária</label></td>
<td><input type="text" id="ch" name="ch" size="6" maxlength="5" value="{$v.ch}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="ajuste2020">Ajuste 2020</label></td>
<td>
<select id="ajuste2020" name="ajuste2020" size="1">
<option value="0" {if $v.ajuste2020 == 0}selected="selected"{/if}>0</option>
<option value="1" {if $v.ajuste2020 == 1}selected="selected"{/if}>1</option>
</select>
</td>
</tr>

<tr>
<td class="coluna_direita">Transporte</td>
<td>
<input type="checkbox" id="benetransporte" name="benetransporte" value="1" {if $v.benetransporte}checked{/if}>
<label for="benetransporte">Possui</label>
</td>
</tr>

<tr>
<td class="coluna_direita">Alimentação</td>
<td>
<input type="checkbox" id="benealimentacao" name="benealimentacao" value="1" {if $v.benealimentacao}checked{/if}>
<label for="benealimentacao">Possui</label>
</td>
</tr>

<tr>
<td class="coluna_direita"><label for="benebolsa">Bolsa</label></td>
<td><input type="text" id="benebolsa" name="benebolsa" size="8" maxlength="5" value="{$v.benebolsa}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="observacoes">Observações</label></td>
<td><textarea id="observacoes" name="observacoes" rows="2" cols="40" maxlength="255">{$v.observacoes}</textarea></td>
</tr>

<tr class="rodape">
<td colspan="2" class="coluna_centralizada">
<input type="submit" value="Salvar">
<input type="button" value="Cancelar" onclick="history.back()">
</td>
</tr>

</tbody>
</table>

</form>

</body>
</html>