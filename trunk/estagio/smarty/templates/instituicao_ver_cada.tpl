<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Ver cada instituição</title>
{literal}
<script>
function barra() {
    var valor = document.getElementById("barra_indice").value;
    window.location="ver_cada.php?indice="+valor;
    return true;
    // alert("Hola" + valor);
}
</script>
{/literal}
</head>

<body>

<div align="center">
<table border="0">
<tbody>

<!--
debug
<tr>
<td colspan="6">{$indice}</td>
</tr>
debug
//-->

<tr>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="primeiro">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="Primeiro">
</form>
</td>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="menos_10">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="- 10">
</form>
</td>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="menos_1">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="- 1">
</form>
</td>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="mais_1">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="+ 1">
</form>
</td>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="mais_10">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="+ 10">
</form>
</td>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="ultimo">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="Último">
</form>
</td>

{if $sistema_autentica == 1}
<td style="background-color:red">
<form name="cabacalho" action="../cancelar/cancela.php" method="post">
<input type="hidden" name="id_instituicao" value="{$id}">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="Excluir">
</form>
</td>
{/if}

</tr>
</tbody>
</table>
</div>


<div align="center">
<table border="1" width="95%">
<tbody>

<tr>
<td width="25%" colspan="2" class="rodape">Instituição</td>
<td colspan="4" class="rodape">{$instituicao}</td>
</tr>

<tr>
<td colspan="2">Endereço</td>
<td colspan="4">{$endereco}</td>
</tr>

<tr>
<td colspan="2">CEP</td>
<td colspan="4">{$cep}</td>
</tr>

<tr>
<td colspan="2">Telefone</td>
<td colspan="4">{$telefone}</td>
</tr>

<tr>
<td colspan="2">Fax</td>
<td colspan="4">{$fax}</td>
</tr>

<tr>
<td colspan="2">Benefícios</td>
<td colspan="4">{$beneficios}</td>
</tr>

<tr>
<td colspan="2">Estágio no final de semana</td>
{if $fim_de_semana eq 0}
<td colspan="4">Não</td>
{elseif $fim_de_semana eq 1}
<td colspan="4">Sim</td>
{elseif $fim_de_semana eq 2}
<td colspan="4">Parcialmente</td>
{/if}
</tr>

<tr>
<td colspan="2">Turma</td>
<td colspan="4"><a href="../../alunos/exibir/alunos_instituicao.php?id_instituicao={$id}&periodo={$turma}">{$turma}</a></td>
</tr>

<tr>
<td colspan="2">Área</td>
<td colspan="4"><a href="../../areas/exibir/instituicoes.php?id_area={$id_area}">{$area}</a></td>
</tr>

<tr>
<td colspan="2">Convênio</td>
<td colspan="4"><a href="http://www.pr1.ufrj.br/estagios/info.php?codEmpresa={$convenio}">{$convenio}</a></td>
</tr>

{if $sistema_autentica == 1}
<form name="modifica_instituicao" action="../atualizar/modifica.php?id_instituicao={$id}" method="post">
<tr class="rodape">
<td colspan="6" class="rodape">
<input type="submit" name="submit" value="Modificar instituição">
</td>
</tr>
</form>
{/if}

</tbody>
</table>
</div>

{* Professores *}
{if !empty($inst_professores)}
<div align="center">
<table border="1" width="90%">
<caption>Professores</caption>
<tbody>
{section name=i loop=$inst_professores}
<tr>
<td>{$inst_professores[i].nome}</td>
<td style='text-align:center;'>{$inst_professores[i].periodo}</td>
</tr>
{/section}
</tbody>
</table>
</div>
{/if}
{* Fim de professores *}

{* Superivosres *}
{if !empty($inst_supervisores)}
<div align="center">
<table border="1" width="90%">
<caption>Supervisores</caption>
<tbody>

{section name=i loop=$inst_supervisores}
<tr>
<td width="10%" style="text-align:right">{$inst_supervisores[i].cress}</td>
<td><a href="../../assistentes/exibir/ver_cada.php?id_supervisor={$inst_supervisores[i].id}">{$inst_supervisores[i].nome}</a></td>

{if $sistema_autentica == 1}
<td width="10%" style="text-align:center">
<a href="../../assistentes/exibir/ver_cada.php?id_supervisor={$inst_supervisores[i].id}">Modifica</a>
</td>

<td width="10%" style="text-align:center">
<a href="../../assistentes/cancelar/inst_supervisor.php?id_supervisor={$inst_supervisores[i].id}&id_instituicao={$id}">Excluir</a>
</td>
{/if}

</tr>
{/section}

</tbody>
</table>
</div>
{/if}
{* Fim de supervisores *}

{if $sistema_autentica == 1}
<div align="center">
<table border='1'>
<tbody>

<form name="supervisores" id="supervisores" action="../../instituicoes/exibir/ver_cada.php" method="post">
<tr class="rodape">

<td colspan="4" class="coluna_centralizada">
<select name='id_supervisor' size='1'>
<option value='0'>Selecione supervisor para inserir na instituição</option>
{section name=i loop=$supervisores}
<option value={$supervisores[i].id}>{$supervisores[i].nome}</option>
{/section}
</select>
<input type="hidden" name="id_instituicao" value="{$id}">
<input type="submit" name="submit" id="submit" value="Inserir supervisor">
</td>

</tr>
</form>

</tbody>
</table>
</div>
{/if}

</body>

</html>