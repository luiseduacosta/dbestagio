<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Estágio #{$e.id}</title>
</head>
<body>

<a href="javascript:history.back();">Voltar</a>

<div align="center">
<h3>Estágio #{$e.id}</h3>

<table class="ficha">
<tbody>

<tr>
<td class="coluna_direita">Período</td>
<td>{$e.periodo}</td>
</tr>

<tr>
<td class="coluna_direita">Aluno</td>
<td>
{if $smarty.cookies.usuario}
<a href="../../alunos/exibir/ver_cada.php?aluno_id={$e.aluno_id}">{$e.aluno_nome}</a>
{else}{$e.aluno_nome}{/if}
<span style="font-size:85%">({$e.registro})</span>
</td>
</tr>

<tr>
<td class="coluna_direita">Instituição</td>
<td>
<a href="../../instituicoes/exibir/ver_cada.php?id_instituicao={$e.instituicao_id}">{$e.instituicao}</a>
</td>
</tr>

<tr>
<td class="coluna_direita">Supervisor</td>
<td>{$e.supervisor}</td>
</tr>

<tr>
<td class="coluna_direita">Professor</td>
<td>{$e.professor}</td>
</tr>

<tr>
<td class="coluna_direita">Nível</td>
<td>{$e.nivel}</td>
</tr>

<tr>
<td class="coluna_direita">TC</td>
<td>{$e.tc}</td>
</tr>

<tr>
<td class="coluna_direita">TC solicitação</td>
<td>{$e.tc_solicitacao}</td>
</tr>

<tr>
<td class="coluna_direita">Nota</td>
<td>{$e.nota}</td>
</tr>

<tr>
<td class="coluna_direita">Carga horária</td>
<td>{$e.ch}</td>
</tr>

<tr>
<td class="coluna_direita">Ajuste 2020</td>
<td>{$e.ajuste2020}</td>
</tr>

<tr>
<td class="coluna_direita">Transporte</td>
<td>{if $e.benetransporte}Sim{else}Não{/if}</td>
</tr>

<tr>
<td class="coluna_direita">Alimentação</td>
<td>{if $e.benealimentacao}Sim{else}Não{/if}</td>
</tr>

<tr>
<td class="coluna_direita">Bolsa</td>
<td>{$e.benebolsa}</td>
</tr>

<tr>
<td class="coluna_direita">Observações</td>
<td>{$e.observacoes}</td>
</tr>

</tbody>
</table>

<p><a href="../atualizar/atualiza.php?id={$e.id}">Editar estágio</a></p>
</div>

</body>
</html>