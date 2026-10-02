<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../estagio.css" rel="stylesheet" type="text/css">
<title>Editar configuração do sistema</title>
</head>
<body>

<div align="center">
<h3>Editar configuração do sistema</h3>
</div>

{if $erros}
<ul style="color:#900;">
{foreach item=erro from=$erros}
<li>{$erro}</li>
{/foreach}
</ul>
{/if}

<span style="font-size:85%;font-style:italic">Datas devem ser informadas no formato AAAA-MM-DD (ex.: 2024-03-18).</span>

<form name="form_config" action="configuracao_edita.php" method="post">

<table class="ficha">
<tbody>

<tr>
<td class="coluna_direita"><label for="instituicao">Instituição</label></td>
<td><input type="text" id="instituicao" name="instituicao" size="40" maxlength="50" value="{$v.instituicao}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="instituicao_curso">Instituição do curso</label></td>
<td><input type="text" id="instituicao_curso" name="instituicao_curso" size="40" maxlength="50" value="{$v.instituicao_curso}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="mural_periodo_atual">Período atual do mural</label></td>
<td><input type="text" id="mural_periodo_atual" name="mural_periodo_atual" size="6" maxlength="6" value="{$v.mural_periodo_atual}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="curso_turma_atual">Turma atual do curso</label></td>
<td><input type="text" id="curso_turma_atual" name="curso_turma_atual" size="2" maxlength="2" value="{$v.curso_turma_atual}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="curso_abertura_inscricoes">Abertura das inscrições do curso</label></td>
<td><input type="text" id="curso_abertura_inscricoes" name="curso_abertura_inscricoes" size="10" maxlength="10" value="{$v.curso_abertura_inscricoes}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="curso_encerramento_inscricoes">Encerramento das inscrições do curso</label></td>
<td><input type="text" id="curso_encerramento_inscricoes" name="curso_encerramento_inscricoes" size="10" maxlength="10" value="{$v.curso_encerramento_inscricoes}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="termo_compromisso_periodo">Período do termo de compromisso</label></td>
<td><input type="text" id="termo_compromisso_periodo" name="termo_compromisso_periodo" size="6" maxlength="6" value="{$v.termo_compromisso_periodo}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="termo_compromisso_inicio">Início do termo de compromisso</label></td>
<td><input type="text" id="termo_compromisso_inicio" name="termo_compromisso_inicio" size="10" maxlength="10" value="{$v.termo_compromisso_inicio}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="termo_compromisso_final">Finalização do termo de compromisso</label></td>
<td><input type="text" id="termo_compromisso_final" name="termo_compromisso_final" size="10" maxlength="10" value="{$v.termo_compromisso_final}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="periodo_calendario_academico">Período do calendário acadêmico</label></td>
<td><input type="text" id="periodo_calendario_academico" name="periodo_calendario_academico" size="6" maxlength="6" value="{$v.periodo_calendario_academico}"></td>
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