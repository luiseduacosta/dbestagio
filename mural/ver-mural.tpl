<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<title>Mural de ofertas de estágio</title>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta name="author" content="Luis Acosta">
	<meta name="generator" content="screem 0.12.1">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<link href="../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
	<style type="text/css">
		@import url("mural.css");
	</style>
	<link rel="stylesheet" type="text/css" href="../lib/mygosumenu/1.0/example1.css" />
	<script type="text/javascript" src="../lib/mygosumenu/ie5.js"></script>
	<script type="text/javascript" src="../lib/mygosumenu/1.0/DropDownMenu1.js"></script>

	{literal}
	<script language="JavaScript" type="text/javascript">
		function janelaInsere() {
			var insere = document.getElementById("registroInserido").value;
			if (insere != "") {
				var texto = document.getElementById("alunoInscrito");
				texto.setAttribute("style", "font-weight:bold; background-color=yellow; color: red");
				texto.style.cssText = "font-weight:bold; background-color:yellow; color:red";
				texto.innerHTML = "Aluna(o) " + insere + " inscrita(o) em seleção de estágio";
				insere = "";
			}
			return true;
		}
	</script>
	{/literal}

</head>

<body id="corpo" onLoad="return janelaInsere();">

	<form name="confirmaInscricao" id="confirmaInscricao" action="#" method="post" enctype="text/plain">
		<input type="hidden" name="registroInserido" id="registroInserido" value="{$insere}">
	</form>

	<span id="alunoInscrito"></span>
	
	{if $sistema_autentica == 1}
	{include file="mural_menu.tpl"}
	{/if}

	<h1>Mural de estágios - Turma {$periodo_atual}</h1>

	<p style="font-size:100%;background-color:#e7e1ae">Clique <a
			href="http://www.pr1.ufrj.br/estagios/busca.php">aqui</a>
		e selecione o curso de Serviço Social para ver as instituições conveniadas com a UFRJ
	</p>

	<p>São {$totalVagas} vagas e {$totalAlunos} alunos ({$alunos_novos} novos e {$alunosVelhos} estagiarios) procurando
		estágio
	</p>

	<table id="mural" class="display">

		<thead>
			<tr>
				<th>Instituição</th>
				<th>Vagas</th>
				<th>Inscritos</th>
				<th>Benefícios</th>
				<th>Encerramento</th>
				<th>Seleção</th>
			</tr>
		</thead>

		<tbody>

		{section name=item loop=$instituicao}
			<tr{if $instituicao[item].convenio == 0} style="background-color:#fdb9b9"{else} style="background-color:#c9f5bf"{/if}>
				<td><a
						href="ver_cada.php?instituicao_id={$instituicao[item].instituicao_id}">{$instituicao[item].instituicao}</a>
				</td>
				<td class="coluna_centralizada">{$instituicao[item].vagas}</td>
				<td class="coluna_centralizada"><a
						href="listaInscritos.php?muralestagio_id={$instituicao[item].mural_estagio_id}">{$instituicao[item].quantidade_alunos}</a>
				</td>
				<td>{$instituicao[item].beneficios}</td>

				<td class="coluna_centralizada" data-order="{$instituicao[item].data_inscricao_iso}">
					{if $instituicao[item].data_inscricao == ''}
					Sem data
					{else}
					{$instituicao[item].data_inscricao}
					{/if}
				</td>

				<td class="coluna_centralizada" data-order="{$instituicao[item].data_selecao_iso}">
					{if $instituicao[item].data_selecao == ''}
					Sem data
					{else}
					{$instituicao[item].data_selecao} Horário: {$instituicao[item].horario_selecao}
					{/if}
				</td>
			</tr>
		{/section}

		</tbody>

	</table>

	{literal}
	<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
	<script src="../libjs/datatables/dataTables.min.js"></script>
	<script>
	$(document).ready(function () {
		$('#mural').DataTable({
			language: { url: '../libjs/datatables/pt-BR.json' },
			pageLength: 25,
			lengthMenu: [10, 25, 50, 100],
			order: []
		});
	});
	</script>
	{/literal}

</body>

</html>
