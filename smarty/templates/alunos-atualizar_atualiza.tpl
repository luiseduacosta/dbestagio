<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Modificar aluno - {$aluno_nome}</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">

{literal}
<script language="JavaScript" type="text/javascript" src="../../lib/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="../../lib/jquery.maskedinput-1.2.1.pack.js"></script>
<script language="JavaScript" type="text/javascript">
$(function() {
	$("#telefone").mask("(99)9999.9999");
 	$("#celular").mask("(99)99999.9999");
	$("#cep").mask("99999-999");
	$("#cpf").mask("999999999-99");
});
</script>

<script type="text/javascript">
$(document).ready(function() {
	$("#instituicao_id").change(function() {
	$("#supervisor_id").html("<option value='sda'>Procurando :::::::</option>");
	$.post('exibir_super.php',
	{ id_estagio : $(this).val() },
	function(resposta){
		$("select[@name=supervisor_id]").html(resposta);
		}
		);
	});
});
</script>

<script language="JavaScript" type="text/javascript">
function verificaPeriodo() {
    var tamanho = document.inserir_aluno.periodo.value.length;
    return;
}

function aluno() {
    var aluno_id = document.form_estagiarios.aluno_id.value;
    window.location="../../alunos/atualizar/atualizar.php?aluno_id=" + aluno_id;
}
</script>
{/literal}

{* Calendario *}
<link rel="stylesheet" type="text/css" href="../../epoch/epoch_styles.css" />
{literal}
<script type="text/javascript" src="../../epoch/epoch_classes.js"></script>
<script language="JavaScript" type="text/javascript">

var calendar1;
window.onload = function() {
	calendar1 = new Epoch('nascimento', 'popup', document.getElementById('nascimento'),false);
}

</script>
{/literal}

</head>

<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Alunos</p>
			<h1>Modificar aluno(a): {$aluno_nome}<span>DRE {$registro}</span></h1>
		</div>
	</header>

	<div class="action-row" style="margin-top:0;">
		<a class="btn btn-secondary" href="ver_cada.php?aluno_id={$aluno_id}">Voltar para o aluno</a>
	</div>

	{* Modifica dados do aluno *}
	<form action="atualizar.php?acao=1" name="atualiza_aluno" id="atualiza_aluno" method="post">

	<table class="ficha card-table">
		<tbody>

		<tr>
		<td colspan="2"><strong>Aluno(a):</strong> {$aluno_nome} &nbsp;·&nbsp; <strong>DRE:</strong> {$registro}</td>
		</tr>

		<tr>
		<td><label for="registro">Registro</label></td>
		<td><input type="text" name="registro" id="registro" size="10" maxlength="10" value="{$registro}"></td>
		</tr>

		<tr>
		<td><label for="nome">Nome</label></td>
		<td><input type="text" name="nome" id="nome" size="30" maxlength="50" value="{$aluno_nome}"></td>
		</tr>

		<tr>
		<td>Telefone / Celular</td>
		<td>
		<input type="text" name="codigo_telefone" size="2" maxlength="2" value="{$codigo_telefone}">
		<input type="text" name="telefone" id="telefone" size="9" maxlength="9" value="{$telefone}">
		&nbsp;Celular:
		<input type="text" name="codigo_celular" size="2" maxlength="2" value="{$codigo_celular}">
		<input type="text" name="celular" id="celular" size="9" maxlength="9" value="{$celular}">
		</td>
		</tr>

		<tr>
		<td><label for="email">E-mail</label></td>
		<td><input type="text" name="email" id="email" size="30" maxlength="50" value="{$email}"></td>
		</tr>

		<tr>
		<td><label for="cpf">CPF</label></td>
		<td><input type="text" maxlength="12" size="12" name="cpf" id="cpf" value='{$cpf}' /></td>
		</tr>

		<tr>
		<td>Carteira de identidade</td>
		<td>
		<input type="text" maxlength="15" size="15" name="identidade" id="identidade" value='{$identidade}' />
		&nbsp;Orgão:
		<input type="text" maxlength="10" size="10" name="orgao" id="orgao" value='{$orgao}' />
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
		<input type="text" maxlength="50" size="30" name="endereco" id="endereco" value='{$endereco}' />
		&nbsp;CEP: <input type="text" maxlength="9" size="9" name="cep" id="cep" value='{$cep}' />
		</td>
		</tr>

		<tr>
		<td>Bairro / Município</td>
		<td>
		<input type="text" maxlength="30" size="15" name="bairro" id="bairro" value='{$bairro}' />
		&nbsp;Município:
		<input type="text" maxlength="30" size="20" name="municipio" id="municipio" value='{$municipio}' />
		</td>
		</tr>

		<tr>
		<td><label for="turno_id">Turno</label></td>
		<td>
		<select name="turno_id" id="turno_id" size="1">
		<option value="0">Selecione turno</option>
		{section name=t loop=$turnos}
		<option value="{$turnos[t].id}" {if $turnos[t].id == $turno_id}selected="selected"{/if}>{$turnos[t].turno}</option>
		{/section}
		</select>
		</td>
		</tr>

		<tr>
		<td><label for="observacoes">Observações</label></td>
		<td><textarea name="observacoes" id="observacoes" rows="3" cols="60">{$observacoes}</textarea></td>
		</tr>

		<tr class="rodape">
		<td colspan="2">
		<input type="hidden" name="acao" value="1">
		<input type="hidden" name="origem" value={$origem}>
		<input type="hidden" name="aluno_id" value="{$aluno_id}">
		<button type="submit" name="submit" class="btn">Salvar alterações</button>
		<input type="button" value="Cancelar" onclick="window.location='ver_cada.php?aluno_id={$aluno_id}'">
		</td>
		</tr>

		</tbody>
	</table>

	</form>

	{* Modifica dados dos campos de estágio *}
	{if $estagiarios}
	<section class="table-shell" aria-label="Estágios do aluno">
		<p class="eyebrow">Estágios registrados</p>
		<table class="dt-table">
			<thead>
				<tr>
					<th>Período</th>
					<th>TC</th>
					<th>Estágio</th>
					<th>ch</th>
					<th>Nota</th>
					<th>Instituição</th>
					{if $isAdmin}
					<th data-orderable="false">Ações</th>
					{/if}
				</tr>
			</thead>
			<tbody>
				{section name=elemento loop=$estagiarios}
				<tr>
					<td class="coluna_centralizada">{$estagiarios[elemento].periodo}</td>
					<td class="coluna_centralizada">{$estagiarios[elemento].tc}</td>
					<td class="coluna_centralizada">{$estagiarios[elemento].nivel}</td>
					<td class="coluna_direita">{$estagiarios[elemento].ch}</td>
					<td class="coluna_direita">{$estagiarios[elemento].nota}</td>
					<td>{$estagiarios[elemento].instituicao}</td>
					{if $isAdmin}
					<td>
						<span class="action-links">
						<a href="atualizar/atualiza_estagio.php?estagiario_id={$estagiarios[elemento].id}&aluno_id={$aluno_id}">Modificar</a>
						<a class="is-danger" href="cancelar/cancela_estagio.php?estagiario_id={$estagiarios[elemento].id}&aluno_id={$aluno_id}" onclick="return confirm('Excluir este estágio?');">Excluir</a>
						</span>
					</td>
					{/if}
				</tr>
				{/section}
			</tbody>
		</table>
	</section>
	{/if}

	{if $isAdmin}
	{* Inserir novos campos de estagio do aluno *}
	<form name="form_insere_estagio" id="form_insere_estagio" action="inserir/acrescentar_estagio.php" method="post">

	<table class="ficha card-table">
		<tbody>

		<tr>
		<td colspan="2">Inserir estágio</td>
		</tr>

		<tr>
		<td><label for="periodo">Período</label></td>
		<td>
		<input type="text" name="periodo" id="periodo" size="6" maxlength="6" onChange="return verificaPeriodo();">
		Formato: AAAA-1 ou 2
		</td>
		</tr>

		<tr>
		<td>Termo compromisso</td>
		<td>
		Sim <input type="radio" name="tc" value="1">
		Nao <input type="radio" name="tc" value="0" checked>
		</td>
		</tr>

		<tr>
		<td>Nível</td>
		<td>
		Estágio I   <input type="radio" name="nivel" value="1">
		Estágio II  <input type="radio" name="nivel" value="2">
		Estágio III <input type="radio" name="nivel" value="3">
		Estágio IV  <input type="radio" name="nivel" value="4">
		</td>
		</tr>

		<tr>
		<td><label for="instituicao_id">Instituição</label></td>
		<td>
		<select id="instituicao_id" name="instituicao_id" size="1">
		<option value="0">Selecione instituição</option>
		{section name=elemento loop=$instituicoes}
		<option value="{$instituicoes[elemento].instituicao_id}">
		{$instituicoes[elemento].instituicao|truncate:50}</option>
		{/section}
		</select>
		</td>
		</tr>

		<tr>
		<td><label for="supervisor_id">Supervisor</label></td>
		<td>
		<select id='supervisor_id' name='supervisor_id' size=1>
		<option id='opcoes' value='0'>Selecione supervisor</option>
		</select>
		</td>
		</tr>

		<tr>
		<td>Supervisor</td>
		<td>
		<select name="supervisor_id" size="1">
		<option value="0">Selecione supervisor</option>
		{section name=elemento loop=$supervisores}
		<option value="{$supervisores[elemento].supervisor_id}">
		{$supervisores[elemento].supervisor|truncate:50}</option>
		{/section}
		</select>
		</td>
		</tr>

		<tr>
		<td><label for="professor_id">Professor</label></td>
		<td>
		<select name="professor_id" id="professor_id" size="1">
		<option value="0">Selecione professor</option>
		{section name=elemento loop=$professores}
		<option value="{$professores[elemento].professor_id}">
		{$professores[elemento].professor|truncate:50}</option>
		{/section}
		</select>
		</td>
		</tr>

		<tr>
		<td>Avaliação</td>
		<td>
		Nota (decimal): <input type="text" name="nota" id="nota" size="5" maxlength="5" value="0,00">
		&nbsp;Carga horária (inteiro): <input type="text" name="ch" id="ch" size="5" maxlength="5" value="0">
		</td>
		</tr>

		<tr class="rodape">
		<td colspan="2">
		<input type="hidden" name="aluno_id" value="{$aluno_id}">
		<input type="hidden" name="origem" value="{$origem}">
		<input type="hidden" name="nome" value="{$aluno_nome}">
		<input type="hidden" name="registro" value="{$registro}">
		<button type="submit" name="submit" class="btn">Inserir estágio</button>
		</td>
		</tr>

		</tbody>
	</table>

	</form>
	{/if}

</main>

</body>
</html>
