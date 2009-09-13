<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="pt-br">
<head>
	<meta content="text/html;" http-equiv="content-type">
	<title>Alunos</title>
	<meta content="author" name="Luis Acosta" >

	<style type="text/css">
	@import url("../../estagio.css");
	</style>

{literal}
<script type="text/javascript" src="../../lib/jquery.js">
</script>
<script type="text/javascript" src="../../lib/jquery.quicksearch.js">
</script>
<script type="text/javascript" src="../../lib/jquery.tablehover.js">
</script>
	
<script type="text/javascript">
	$(document).ready(function(){

    $("#alunos").tableHover();
		
    $("#alunos tbody tr td.nome").quicksearch({
    	hideElement: 'parent',
        position: 'prepend',
        attached: '#busca',
        focusOnLoad: true,
        stripeRowClass: ['resaltado', 'normal'],
        loaderText: 'Aguarde...',
        labelText: 'Aluno:'
        });    
    });
    </script>

	<script language="Javascript" type="text/javascript">
	function get_valor() {
		var browserName=navigator.appName;
		var browserVer=navigator.appVersion.substring(0,1);
		var navegador=browserName+browserVer;
		if(navegador == "Microsoft Internet Explorer4") {
			valor_ordem=document.form_turno.ordem.value;
			valor_turno=document.form_turno.turno.value;
			valor_nivel=document.form_nivel.nivel.value;
			valor_instituicao=document.form_instituicao.instituicao.value;
			valor_professor=document.form_professor.professor.value;
			valor_area=document.form_area.id_area.value;
			valor_periodo=document.form_periodo.periodo.value;
	    } else {
	       	valor_ordem=document.getElementById("ordem").value;
	       	valor_turno=document.getElementById("turno").value;
			valor_nivel=document.getElementById("nivel").value;
			valor_instituicao=document.getElementById("instituicao").value;
			valor_professor=document.getElementById("professor").value;
			valor_area=document.getElementById("id_area").value;
			valor_periodo=document.getElementById("periodo").value;
		}
		// alert("Turno " +valor_turno+ " Nivel " +valor_nivel+ " Inst " +valor_instituicao+  " Professor " +valor_professor+ " Periodo " +valor_periodo);
		window.location="listar.php?ordem=" +valor_ordem+ "&periodo=" +valor_periodo+ "&seleciona_turno=" +valor_turno+ "&seleciona_nivel="  +valor_nivel+ "&seleciona_instituicao=" +valor_instituicao+ "&seleciona_professor=" +valor_professor+ "&id_area=" +valor_area+ "&seleciona_periodo=" +valor_periodo;
		return false;
	}

	function atualiza_nota() {

		var valor_turno;
		var valor_nivel;
		var valor_instituicao;
		var valor_professor;
		var valor_area;
		var valor_periodo;
		var ordem;
		var valor;
		var nota;
		var ch;
		var nota_id_aluno;
		var nota_id_estagiario;
		
       	valor_turno = document.getElementById("turno").value;
		valor_nivel = document.getElementById("nivel").value;
		valor_instituicao = document.getElementById("instituicao").value;
		valor_professor = document.getElementById("professor").value;
		valor_area = document.getElementById("id_area").value;
		valor_periodo = document.getElementById("periodo").value;
		ordem = document.getElementById("ordem").value;

		valor = "&ordem=" +ordem+ "&seleciona_turno=" +valor_turno+ "&seleciona_nivel=" +valor_nivel+ "&seleciona_instituicao=" +valor_instituicao+ "&seleciona_professor=" +valor_professor+ "&id_area="+ valor_area + "&seleciona_periodo=" +valor_periodo;
		/* alert("Valor " +valor); */

		nota = document.getElementById("nota").value;
		ch = document.getElementById("ch").value;
		nota_id_aluno = document.getElementById("nota_id_aluno").value;
		nota_id_estagiario = document.getElementById("nota_id_estagiario").value;

		window.location="listar.php?id_aluno=" +nota_id_aluno+ "&id_estagiario=" +nota_id_estagiario+ "&nota="  +nota+ "&ch=" +ch+ "&acao=" +1+ valor;
		alert("Atualiza nota: id_aluno " +nota_id_aluno+ " id_estagiario " +nota_id_estagiario+ " nota " +nota+ " ch " +ch+ " acao " +1+ valor);
		return false;
	}

	function atualiza_ch() {

		var valor_turno;
		var valor_nivel;
		var valor_instituicao;
		var valor_professor;
		var valor_area;
		var valor_periodo;
		var ordem;
		var valor;
		var nota;
		var ch;

       	valor_turno = document.getElementById("turno").value;
		valor_nivel = document.getElementById("nivel").value;
		valor_instituicao = document.getElementById("instituicao").value;
		valor_professor = document.getElementById("professor").value;
		valor_area = document.getElementById("id_area").value;
		valor_periodo = document.getElementById("periodo").value;
		ordem = document.getElementById("ordem").value;

		valor = "&ordem=" +ordem+ "&seleciona_turno=" +valor_turno+ "&seleciona_nivel=" +valor_nivel+ "&seleciona_instituicao=" +valor_instituicao+ "&seleciona_professor=" +valor_professor+  "&id_area="+ valor_area + "&seleciona_periodo=" +valor_periodo;
		/* alert("Valor " +valor); */

		nota = document.getElementById("nota").value;
		ch = document.getElementById("ch").value;
		ch_id_aluno = document.getElementById("ch_id_aluno").value;
		ch_id_estagiario = document.getElementById("ch_id_estagiario").value;

		window.location="listar.php?id_aluno=" +ch_id_aluno+ "&id_estagiario=" +ch_id_estagiario+ "&nota="  +nota+ "&ch=" +ch+ "&acao=" +1+ valor;
		alert("Atualiza ch: id_aluno " +ch_id_aluno+ " id_estagiario " +ch_id_estagiario+ " nota " +nota+ " ch " +ch+ " acao " +1+ valor);
		return false;
	}

	function mostrar() {

		var tabela_resumo = document.getElementById('tabela_resumo');
		var botao_resumo = document.getElementById('botao_resumo');
		
		if (tabela_resumo.style.display == "none") {
			tabela_resumo.style.display = "block";
			botao_resumo.value = "Ocultar resumo";
		} else {
			tabela_resumo.style.display = "none";
			botao_resumo.value = "Ver resumo";
		}
	}

	function historia() {

		var tabela_principal = document.getElementById('tabela_principal');
		var tabela_historico = document.getElementById('tabela_historico');
		var botao_historia = document.getElementById('botao_historia');

		if (tabela_principal.style.display == "none") {
			tabela_principal.style.display = "block";
			tabela_historico.style.display = "none";
			botao_historia.value = "Ver histórico";
    		document.cookie = 'historia_estilo=block';
		} else {
			tabela_principal.style.display = "none";
			tabela_historico.style.display = "block";
			botao_historia.value = "Ocultar histórico";
			document.cookie = 'historia_estilo=none';
		}
	}

	</script>
	{/literal}

</head>

<body style="direction: ltr;">

{if $logado == 1}
	<br>
	<a href='email_alunos.php?periodo={$seleciona_periodo}'>E-mail</a>
{/if}

<input type='submit' name='botao_resumo' id='botao_resumo' value='Ver resumo' onClick='mostrar()'>
<input type='submit' name='botao_historia' id='botao_historia' value='Ver histórico' onClick='historia()'>

<input type="hidden" name="ordem" id="ordem" value="{$ordem}">

<div id='tabela_resumo' style='display:none;'>

<table border='1' summary='Alunos que cursaram quatro periodos por quantidade de periodos'>
<caption>Alunos que completaram quatro perí­odos de estágio</caption>
	<colgroup>
		<col class='coluna_centralizada'>
		<col>
		<col class='coluna_centralizada'>
	</colgroup>
	
	<thead>
		<tr>
			<th>Código</th>
			<th>Conceito</th>
			<th>Alunos</th>
		</tr>
	</thead>

	<tbody>

		<tr>
			<td class='coluna_centralizada'>0</td><td>Todos os períodos no mesmo estágio</td><td class='coluna_centralizada'>{$codigo_0}</td>
		</tr>
		<tr>
			<td class='coluna_centralizada'>1</td><td>Todos os perí­odos em diferentes estágios</td><td class='coluna_centralizada'>{$codigo_1}</td>
		</tr>
		<tr>
			<td class='coluna_centralizada'>2</td><td>Dois perí­odos e dois períodos</td><td class='coluna_centralizada'>{$codigo_2}</td>
		</tr>

		<tr>
			<td class='coluna_centralizada'>3</td><td>Dois perí­odos consecutivos no mesmo estágio (1 e 2)</td><td class='coluna_centralizada'>{$codigo_3}</td>
		</tr>
		<tr>
			<td class='coluna_centralizada'>4</td><td>Dois perí­odos consecutivos no mesmo estágio (2 e 3)</td><td class='coluna_centralizada'>{$codigo_4}</td>
		</tr>
		<tr>
			<td class='coluna_centralizada'>5</td><td>Dois perí­odos consecutivos no mesmo estágio (3 e 4)</td><td class='coluna_centralizada'>{$codigo_5}</td>
		</tr>

		<tr>
			<td class='coluna_centralizada'>6</td><td>Três perí­odos consecutivos no mesmo estágio (1, 2 e 3)</td><td class='coluna_centralizada'>{$codigo_6}</td>
		</tr>
		<tr>
			<td class='coluna_centralizada'>7</td><td>Três perí­odos consecutivos no mesmo estágio (2, 3 e 4)</td><td class='coluna_centralizada'>{$codigo_7}</td>
		</tr>
		<tr>
			<th class='coluna_centralizada'>TOTAL</th><th>&nbsp;</th><th class='coluna_centralizada'>{$total}</th>
		</tr>
	</tbody>
</table>

</div>


<div id="busca"></div>

<div class="corpo">

<div class="acima">
<table border="1">
  <tbody>
    <tr>

    <!-- Turno //-->
    <td>
        <select name="seleciona_turno" size="1" id="turno" onChange="return get_valor();">
        {if $seleciona_turno eq 'D'}
            <option value="0">Turno</option>
            <option value="D" selected>Diurno</option>
            <option value="N">Noturno</option>
        {elseif $seleciona_turno eq 'N'}
            <option value="0">Turno</option>
            <option value="D">Diurno</option>
            <option value="N" selected>Noturno</option>
        {else}
            <option value="0" selected>Turno</option>
            <option value="D">Diurno</option>
            <option value="N">Noturno</option>
        {/if}
        </select>
    </td>

    <!-- Niveis //-->
    <td>
        <select name="seleciona_nivel" size="1" id="nivel" onChange="return get_valor();">
        {if $seleciona_nivel eq "1"}
            <option value="0">N&iacute;veis</option>
            <option value="1" selected>1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        {elseif $seleciona_nivel eq "2"}
            <option value="0">N&iacute;veis</option>
            <option value="1">1</option>
            <option value="2" selected>2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        {elseif $seleciona_nivel eq "3"}
            <option value="0">N&iacute;veis</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3" selected>3</option>
            <option value="4">4</option>
        {elseif $seleciona_nivel eq "4"}
            <option value="0">N&iacute;veis</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4" selected>4</option>
        {else}
            <option value="0" selected>N&iacute;veis</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        {/if}
        </select>
    </td>

    <!-- Instituicao //-->
    <td>
        <select name="seleciona_instituicao" size="1" id="instituicao" onChange="return get_valor();">
        {if $seleciona_instituicao eq "0"}
            <option value='0' selected>Instituições</option>
	    <!--
            <option value={$seleciona_instituicao}>{$nome_instituicao|truncate:30}</option>
	    //-->
            {section name=elemento loop=$instituicoes}
            <option value="{$instituicoes[elemento].id_instituicao}">{$instituicoes[elemento].instituicao|truncate:30}</option>
            {/section}
        {else}
            <option value='0'>Instituições</option>
            <option value={$seleciona_instituicao} selected>{$nome_instituicao|truncate:30}</option>
            {section name=elemento loop=$instituicoes}
            <option value="{$instituicoes[elemento].id_instituicao}">{$instituicoes[elemento].instituicao|truncate:30}</option>
            {/section}
        {/if}
        </select>
    </td>

    <!-- Professor //-->
    <td>
        <select name="seleciona_professor" size="1" id="professor" onChange="return get_valor();">
        {if $seleciona_professor eq "0"}
            <option value='0' selected>Professores</option>
	    <!--
            <option value={$seleciona_professor}>{$nome_professor|truncate:30}</option>
	    //-->
            {section name=elemento loop=$professores}
            <option value="{$professores[elemento].id_professor}">{$professores[elemento].nome|truncate:30}</option>
            {/section}
        {else}
            <option value='0'>Professores</option>
            <option value={$seleciona_professor} selected>{$nome_professor|truncate:30}</option>
            {section name=elemento loop=$professores}
            <option value="{$professores[elemento].id_professor}">{$professores[elemento].nome|truncate:30}</option>
            {/section}
        {/if}
        </select>
    </td>

    <!-- Area //-->
    <td>
        <select name="id_area" size="1" id="id_area" onChange="return get_valor();">
        {if $id_area eq "0"}
            <option value='0' selected>Áreas</option>
            {section name=elemento loop=$areas}
            <option value="{$areas[elemento].id_area}">{$areas[elemento].area|truncate:30}</option>
            {/section}
        {else}
            <option value='0'>Áreas</option>
            <option value={$id_area} selected>{$area_selecionada|truncate:30}</option>
            {section name=elemento loop=$areas}
            <option value="{$areas[elemento].id_area}">{$areas[elemento].area|truncate:30}</option>
            {/section}
        {/if}
        </select>
    </td>

    <!-- Periodo //-->
    <td>
        <select name="seleciona_periodo" size="1" id="periodo" onChange="return get_valor();">
		{if $seleciona_periodo eq "" || $seleciona_periodo eq "0"}
	    	<option value="0" selected>Perí­odo</option>
    	    {section name=elemento loop=$matriz_periodo}
    	    <option value={$matriz_periodo[elemento].turma}>{$matriz_periodo[elemento].turma}</option>
    	    {/section}
		{else}
	    	<option value="0">Perí­odo</option>
    	    <option value={$seleciona_periodo} selected>{$seleciona_periodo}</option>
    	    {section name=elemento loop=$matriz_periodo}
    	    <option value={$matriz_periodo[elemento].turma}>{$matriz_periodo[elemento].turma}</option>
    	    {/section}
		{/if}
        </select>

    </td>

    </tr>
  </tbody>
</table>
</div>

<!--
Tabela principal
//-->

{* Variavel na primeira linha da tabela *}
{assign var="criterio" value="&seleciona_instituicao=$seleciona_instituicao&seleciona_periodo=$seleciona_periodo&seleciona_turno=$seleciona_turno&seleciona_nivel=$seleciona_nivel&seleciona_professor=$seleciona_professor&id_area=$id_area&historia_estilo=$historia_estilo"}

<div id='tabela_principal' style='display:block;'>
<table id="alunos" border="1" summary="Lista de alunos">
	<thead>
    <tr>
		{if $logado == 1}
   	    	<th>Editar</th>
		{/if}
    	<th>Id</th>
        <th><a href="?ordem=registro{$criterio}">Registro</a></th>
        <th><a href="?ordem=tc{$criterio}">TC</a></th>
        <th><a href="?ordem=nome{$criterio}">Nome</a></th>
		
		{if $logado == 1}
			<th>Email</th>
			<th>Celular</th>
			<th>Telefone</th>
		{/if}
		
        <th style="text-align:center"><a href="?ordem=nivel{$criterio}">Nível</a></th>
		<th style="text-align:center"><a href="?ordem=codigo{$criterio}">Cod</a></th>
		<th><a href="?ordem=periodo{$criterio}">Perí­odo</a></th>
        <th><a href="?ordem=turno{$criterio}">Turno</a></th>
        <th><a href="?ordem=instituicao{$criterio}">Instituição</a></th>
        <th><a href="?ordem=supervisor{$criterio}">Supervisor</a></th>
        <th><a href="?ordem=area{$criterio}">Área</a></th>
        <th><a href="?ordem=professor{$criterio}">Professor</a></th>

		{if $logado == 1}
    	    <th><a href="?ordem=nota{$criterio}">Nota</a></th>
    	    <th><a href="?ordem=ch{$criterio}">CH</a></th>
		{/if}

    </tr>
	</thead>

    <tbody>
	{assign var = "i" value = 1}
	{section name=i loop=$lista}
	{strip}
	<tr>
	{if $logado == 1}
		<td><a href="../atualizar/atualiza_estagio.php?id_estagiarios={$lista[i].id_estagiario}&id_aluno={$lista[i].id_aluno}">Editar</a></td>
	{/if}
	<td style="text-align:right">{$i++}</td>
	<td style="text-align:right">{$lista[i].registro}</td>
	<td style="text-align:center">{$lista[i].tc}</td>
	<td class="nome"><a href="ver_cada.php?id_aluno={$lista[i].id_aluno}">{$lista[i].nome}</a></td>
	
	{if $logado == 1}
		<td>{$lista[i].email}</td>
		<td style="text-align:right">{$lista[i].telefone}</td>
		<td style="text-align:right">{$lista[i].celular}</td>        
	{/if}
    
	<td style="text-align:center">{$lista[i].nivel}</td>

	<td style="text-align:center">{$lista[i].codigo}</td>

    <td style="text-align:center">{$lista[i].periodo}</td>
    <td style="text-align:center">{$lista[i].turno}</td>
    <td><a href="../../instituicoes/exibir/ver_cada.php?id_instituicao={$lista[i].id_instituicao}">{$lista[i].instituicao}</a></td>
    <td><a href="../../assistentes/exibir/ver_cada.php?id_supervisor={$lista[i].id_supervisor}">{$lista[i].supervisor}</a></td>
    <td>{$lista[i].area}</td>
    <td>{$lista[i].professor}</td>

	{if $logado == 1}
	    <td style="text-align:center">
		{$lista[i].nota}
		</td>

		<td style="text-align:right">
		{$lista[i].ch}
		</td>

	{/if}

	</tr>
	{/strip}
	{/section}
	</tbody>
</table>
</div>

<div id='tabela_historico' style='display:none;'>
<table id="alunos" border="1" summary="Lista de alunos">
	<thead>
	<tr>
		{if $logado == 1}
			<th>Editar</th>
		{/if}
		<th>Id</th>

        <th><a href="?ordem=registro{$criterio}">Registro</a></th>
        <th><a href="?ordem=tc{$criterio}">TC</a></th>
		<th><a href="?ordem=nome{$criterio}">Nome</a></th>
		
		{if $logado == 1}
			<th>Email</th>
			<th>Celular</th>
			<th>Telefone</th>
		{/if}
		
		<th style="text-align:center"><a href="?ordem=nivel{$criterio}">Nível</a></th>

		<th><a href="?ordem=periodo{$criterio}">Período</a></th>

		<th colspan='4' style="text-align:center">Ní­veis</th>

		<th>Cod</th>

		<th style="text-align:center"><a href="?ordem=num_monografia{$criterio}">Mon</a></th>

		{if $logado == 1}
			<th><a href="?ordem=nota{$criterio}">Nota</a></th>
			<th><a href="?ordem=ch{$criterio}">CH</a></th>
		{/if}

		<th id='historico20' colspan="3">Ní­vel 1</th>
		<th id='historico21' colspan="3">Ní­vel 2</th>
		<th id='historico22' colspan="3">Ní­vel 3</th>
		<th id='historico23' colspan="3">Ní­vel 4</th>

		<th><a href="?ordem=titulo{$criterio}">Tí­tulo</a></th>
		<th><a href="?ordem=mono_area{$criterio}">Área</a></th>
		<th><a href="?ordem=mono_periodo{$criterio}">Perí­odo</a></th>

	</tr>
	</thead>
	<tbody>
	
	{assign var = "i" value = 1}
	{section name=i loop=$lista}
	{strip}
	<tr>
	{if $logado == 1}
		<td><a href="../atualizar/atualiza_estagio.php?id_estagiarios={$lista[i].id_estagiario}&id_aluno={$lista[i].id_aluno}">Editar</a></td>
	{/if}
	<td style="text-align:right">{$i++}</td>
	<td style="text-align:right">{$lista[i].registro}</td>
	<td style="text-align:center">{$lista[i].tc}</td>
	<td class="nome"><a href="ver_cada.php?id_aluno={$lista[i].id_aluno}">{$lista[i].nome}</a></td>
	
	{if $logado == 1}
		<td>{$lista[i].email}</td>
		<td style="text-align:right">{$lista[i].telefone}</td>
		<td style="text-align:right">{$lista[i].celular}</td>
	{/if}

	<td style="text-align:center">{$lista[i].nivel}</td>
	<td style="text-align:center">{$lista[i].periodo}</td>
    
	<td style="text-align:right">{$lista[i].nivel1}</td>
	<td style="text-align:right">{$lista[i].nivel2}</td>
	<td style="text-align:right">{$lista[i].nivel3}</td>
	<td style="text-align:right">{$lista[i].nivel4}</td>

	<td style="text-align:center">{$lista[i].codigo}</td>

	<td style="text-align:center"><a href='../../../tcc/monografia/visualizar/ver_monografia.php?codigo={$lista[i].num_monografia}'>{$lista[i].num_monografia}</a></td>

	{if $logado == 1}
		<td style="text-align:center">
		{$lista[i].nota}
		</td>

		<td style="text-align:right">
		{$lista[i].ch}
		</td>
	{/if}

	<td id='historia1' style="text-align:left;">{$lista[i].instituicao1}</td>
	<td id='historia2' style="text-align:left;">{$lista[i].area1}</td>
	<td id='historia3' style="text-align:center;">{$lista[i].periodo1}</td>
	
	<td id='historia4' style="text-align:left;">{$lista[i].instituicao2}</td>
	<td id='historia5' style="text-align:left;">{$lista[i].area2}</td>
	<td id='historia6' style="text-align:center;">{$lista[i].periodo2}</td>
	        
	<td id='historia7' style="text-align:left;">{$lista[i].instituicao3}</td>
	<td id='historia8' style="text-align:left;">{$lista[i].area3}</td>
	<td id='historia9' style="text-align:center;">{$lista[i].periodo3}</td>
	            
	<td id='historia10' style="text-align:left;">{$lista[i].instituicao4}</td>
	<td id='historia11' style="text-align:left;">{$lista[i].area4}</td>
	<td id='historia12' style="text-align:center;">{$lista[i].periodo4}</td>

	{if $lista[i].num_monografia}
		<td style="text-align:left;"><a href='../../../tcc/monografia/visualizar/ver_monografia.php?codigo={$lista[i].num_monografia}'>{$lista[i].titulo}</a></td>
		<td style="text-align:center;">{$lista[i].mono_area}</td>
		<td style="text-align:center;">{$lista[i].mono_periodo}</td>
	{/if}

	</tr>
	{/strip}
	{/section}
	</tbody>
</table>
</div>

</div>

</body>
</html>