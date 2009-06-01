<?php require_once("ajaxInstituicao.php"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">

    <style>
    @import url(domTT.css);
    </style>
    <script type="text/javascript" language="javascript" src="../../domTT/domLib.js"></script>
    <script type="text/javascript" language="javascript" src="../../domTT/domTT.js"></script>
    <script type="text/javascript" language="javascript" src="../../domTT/domTT_drag.js"></script>
    <script type="text/javascript" language="javascript">
    var domTT_classPrefix = 'domTTWin';
    var domTT_closeLink = '<img src="close.gif" style="vertical-align: bottom;" width="16" height="14" />';
    onload = function(in_event)
    {
    // domTT_addPredefined('popup', 'caption', 'Coordenação de estágio', 'content', '<div style="text-align: justify; padding: 2px 5px; font-size: 13px; font-family: Arial; background-color: #FFFFFF;"><span style="font-weight: bold;">Inscrições para o curso de extensão para atualização profissional de supervisores a partir do dia 4 de abril</span><br /></div>', 'type', 'sticky');
    // domTT_activate('popup1', in_event, 'predefined', 'popup', 'x', 230, 'y', 50, 'width', 260, 'delay', 1000);
    // domTT_activate('popup2', in_event, 'caption', 'Hello There!', 'content', '<div style="background-color: #FFFFFF; font-size: 13px; padding: 2px; font-family: Arial;">This is an example of a second popup tooltip onload.  It has a delay twice as long as the first popup.</div>', 'x', 100, 'y', 100, 'width', 150, 'delay', 2000, 'type', 'sticky');
    }
    </script>

    <title>Cadastro de supervisores</title>
<?php $xajax->printJavascript(); ?>
<script language="JavaScript" type="text/javascript">
<!--
function confirma() {
    var nome, email, cress, instituicao;

    nome=document.inscricao.nome.value;
    email=document.inscricao.email.value;
    cress=document.inscricao.cress.value;
    instituicao=document.inscricao.instituicaoNova.value;

    if(nome=="") {
    	alert("Você precisa informar o seu nome");
	document.inscricao.nome.focus();
	return false;
	}

    if(email=="") {
    	alert("É importante o seu e-mail para nossa comunicação");
	document.inscricao.email.focus();
	return false;
	}

    if(cress=="") {
    	alert("Favor informar o seu número de registro no CRESS");
	document.inscricao.cress.focus();
	return false;
	}

    if(instituicao=="") {
    	alert("Informe a instituição em que trabalha");
	document.inscricao.instituicao.focus();
	return false;
	}

    return true;
}

function verifica_nome() {
    var nome;
    nome=document.inscricao.nome.value;
    if(nome=="")
    {
	alert("Você precisa informar o seu nome");
	document.inscricao.nome.focus();
	return false;
    }
    else
    {
	return true;
    }
}

function verifica_email() {
    var email;
    email=document.inscricao.email.value;
    if(email=="")
    {
	alert("É importante o e-mail para nossa comunicação");
	document.inscricao.email.focus();
	return false;
    }
    else
    {
	return true;
    }
}

function verifica_cress() {
    var cress;
    cress=document.inscricao.cress.value;
    if(cress=="")
    {
	alert("Você precisa informar o número de registro no CRESS 7a. região");
	document.inscricao.cress.focus();
	return false;
    }
    else
    {
	return true;
    }
}

function verifica_instituicao() {
    var instituicao;
    instituicao=document.inscricao.instituicaoNova.value;
    if(instituicao=="") {
		alert("Você precisa informar a instituição na que trabalha");
		document.inscricao.instituicaoNova.focus();
		return false;
    } else {
			return true;
    }
}

function contacarateres() {
    var quantidade = document.getElementById("cep").value.length;
	var caraterDigitado = document.getElementById("cep").value;
	if (quantidade == 5) {
		caraterDigitado = document.getElementById("cep").value;
		caraterDigitado = caraterDigitado + "-";
	}
	var carateres = document.getElementById("quantidade");
	carateres.innerHTML = "Formato: 99999-999. Quantidade de carateres digitados: " +quantidade;
	var cep = document.getElementById("cep");
	cep.value = caraterDigitado;
    if(quantidade > 9) {
		alert("O limite do campo é de 9 carateres");
	}
}

function verInstituicao() {
    var id_instituicao = document.getElementById("id_instituicao").value;

    var num_instituicao = document.getElementById("num_instituicao");
    num_instituicao.value = "";
	
    var instituicao = document.getElementById("instituicaoNova");
    instituicao.value = "";

    var endereco = document.getElementById("instituicao_endereco");
    endereco.value = "";
    
    var bairro = document.getElementById("instituicao_bairro");
    bairro.value = "";
    
    var municipio = document.getElementById("instituicao_municipio");
    municipio.value = "";

    var cep = document.getElementById("instituicao_cep");
    cep.value = "";

    var telefone = document.getElementById("instituicao_telefone");
    telefone.value = "";

    var fax = document.getElementById("instituicao_fax");
    fax.value = "";

    var beneficio = document.getElementById("instituicao_beneficio");
    beneficio.value = "";

    var fim_de_semana = document.getElementById("fim_de_semana");
    fax.value = "";
    
    xajax_ver_Instituicao(id_instituicao);
    // alert(id_instituicao);
    return true;
}

function ver_Supervisor() {
    var id_supervisor = document.getElementById("id_supervisor").value;

    var num_supervisor = document.getElementById("num_supervisor");
    num_supervisor.value = "";
	
    var supervisor = document.getElementById("supervisor_novo");
    supervisor.value = "";

    var endereco = document.getElementById("endereco");
    endereco.value = "";
    
    var bairro = document.getElementById("bairro");
    bairro.value = "";
    
    var municipio = document.getElementById("municipio");
    municipio.value = "";

    var cep = document.getElementById("cep");
    cep.value = "";

    var codigo_tel = document.getElementById("codigo_tel");
    codigo_tel.value = "";

    var telefone = document.getElementById("telefone");
    telefone.value = "";

    var codigo_cel = document.getElementById("codigo_cel");
    codigo_cel.value = "";

    var celular = document.getElementById("celular");
    celular.value = "";

    var email = document.getElementById("email");
    email.value = "";

    var escola = document.getElementById("escola");
    escola.value = "";
    
    var ano_formatura = document.getElementById("ano_formatura");
    ano_formatura.value = "";

    var cress = document.getElementById("cress");
    cress.value = "";

    var regiao = document.getElementById("regiao");
    regiao.value = "";
    
    var outros_estudos = document.getElementById("outros_estudos");
    outros_estudos.value = "";

    var area_curso = document.getElementById("area_curso");
    area_curso.value = "";

    var ano_curso = document.getElementById("ano_curso");
    ano_curso.value = "";
    
    xajax_ver_Supervisor(id_supervisor);

    // alert(id_supervisor);
    alert(area_curso);
    
    return true;
}

//-->
</script>
<style type="text/css">
@import url(../../estagio.css);
</style>

</head>

<body>

<?php

include_once("../../setup.php");
// include_once("../../db.inc");
include_once("../../autoriza.inc");

if($sistema_autentica == 0) {
	echo "<meta HTTP-EQUIV='refresh' CONTENT='0,URL=http://$url/estagio/login.php'>";
	exit;
}

?>

<form action="inserir.php" name="cadastro" id="cadastro" method="post">

<div align="center">
<table border="1">
<tbody>

<tr>
<td colspan="2" class="rodape">
<b>Dados do supervisor</b>
</td>
</tr>

<?php
$sql  = "select id, nome ";
$sql .= " from supervisores ";
$sql .= " order by nome";
$resultado = $db->Execute($sql);
if($resultado_=== false) die ("Não foi possível consultar a tabela supervisores");
?>

<tr>
<td>Selecione o nome do Assistente Social: </td>
<td>
<select nome="id_supervisor" id="id_supervisor" size="1" onChange="return ver_Supervisor();">
<option value=0>Se já está cadastrado selecione aqui, caso contrário digite a informação</option>
<?php
while (!$resultado->EOF) {
    $id_supervisor = $resultado->fields['id'];
    $supervisor    = $resultado->fields['nome'];
    echo "
    <option value=$id_supervisor>$supervisor</option>
    ";
    $resultado->MoveNext();
}
?>
</select>
</td>
</tr>

<?php
// echo $num_supervisor = $_REQUEST['num_supervisor'];
?>

<input type="hidden" name="num_supervisor" id="num_supervisor" value='<?php echo $num_supervisor; ?>'>

<tr>
<td width="30%">Nome: </td>
<td width="70%"><input type="text" name="nome" id="supervisor_novo" maxlength="70" size="50" onBlur="return verifica_nome()"></td>
</tr>

<tr>
<td>Endereço: </td>
<td><input type="text" name="endereco" id="endereco" maxlength="100" size="50"></td>
</tr>

<tr>
<td>Bairro: </td>
<td><input type="text" name="bairro" id="bairro" maxlength="30" size="30"></td>
</tr>

<tr>
<td>Município: </td>
<td><input type="text" name="municipio" id="municipio" maxlength="30" size="30"></td>
</tr>

<tr>
<td>CEP:</td>
<td><input type="text" name="cep" id="cep" maxlength="9" size="9" onkeyup="contacarateres();">
<span id="quantidade"></span></td>
</tr>

<tr>
<td>Telefone: </td>
<td>
Código: <input type="text" name="codigo_tel" id="codigo_tel" maxlength="2" size="2" value="21">
<input type="text" name="telefone" id="telefone" maxlength="9" size="9"></td>
</tr>

<tr>
<td>Celular: </td>
<td>
Código: <input type="text" name="codigo_cel" id="codigo_cel" maxlength="2" size="2" value="21">
<input type="text" name="celular" id="celular" maxlength="9" size="9"></td>
</tr>

<tr>
<td>E-mail: </td>
<td><input type="text" name="email" id="email" maxlength="50" size="50" onBlur="return verifica_email();"></td>
</tr>

<tr>
<td>Escola em que se formou: </td>
<td><input type="text" name="escola" id="escola" maxlength="70" size="30"></td>
</tr>

<tr>
<td>Ano em que se formou: </td>
<td><input type="text" name="ano_formatura" id="ano_formatura" maxlength="4" size="4"></td>
</tr>

<tr>
<td>No. de registro no CRESS</td>
<td>
<input type="text" name="cress" id="cress" maxlength="15" size="10" onBlur="return verifica_cress()">
<span style="text-align:right">Região: </span><input type="text" name="regiao" id="regiao" maxlength="2" size="1" value="7">
</td>
</tr>

<tr>
<td>Outros estudos realizados:</td>
<td>
<input type="radio" name="outros_estudos" id="outros_estudos" value="especialização">
Especialização
<input type="radio" name="outros_estudos" id="outros_estudos" value="mestrado">
Mestrado
<input type="radio" name="outros_estudos" id="outros_estudos" value="doutorado">
Doutorado
</td>
</tr>

<tr>
<td>Área em que foi realizado o curso anteriormente mencionado</td>
<td><input type="text" name="area_curso" id="area_curso" maxlength="40" size="30"></td>
</tr>

<tr>
<td>Ano em que foi concluído o curso</td>
<td><input type="text" name="ano_curso" id="ano_curso" maxlength="4" size="4"></td>
</tr>

<tr>
<td colspan="2" class="coluna_centralizada">
<input type="submit" name="enviar" value="Confirmar" onClick="return confirma()">
<input type="reset" name="limpar" value="Limpar">
</td>
</tr>

</tbody>
</table>
</div>

</form>

</body>

</html>
