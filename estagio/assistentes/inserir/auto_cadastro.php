<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    <title>Cadastro de supervisores</title>
	<script type="text/javascript" src="../../lib/jquery.js"></script>
	<script type="text/javascript" src="../../lib/alphanumeric/jquery.alphanumeric.pack.js"></script>

<script language="JavaScript" type="text/javascript">
$(document).ready(function() {
	$('#cress').numeric();
	$('#ano_formatura').numeric();
	$('#ano_curso').numeric();
});
</script>
<script language="JavaScript" type="text/javascript">
<!--
function confirma() {
    var nome, email, cress, instituicao;

    nome=document.getElementById('nome').value;
    email=document.getElementById('email').value;
    cress=document.getElementById('cress').value;
    escola=document.getElementById('escola').value;
    ano_formatura=document.getElementById('ano_formatura').value;
    id_instituicao=document.getElementById('id_instituicao').value;

    if (nome=="") {
    	alert("Você precisa informar o seu nome");
		document.cadastro.nome.focus();
		return false;
	}

    if (email=="") {
    	alert("É importante o seu e-mail para nossa comunicação");
		document.cadastro.email.focus();
		return false;
	}

    if (cress=="") {
    	alert("Favor informar o seu número de registro no CRESS");
		document.cadastro.cress.focus();
		return false;
	}

    if (escola=="") {
    	alert("Em qual escola fez a sua graduação");
		document.cadastro.escola.focus();
		return false;
	}
    
    if (ano_formatura=="") {
    	alert("Quando se formou");
		document.cadastro.ano_formatura.focus();
		return false;
	}

    if (id_instituicao=="") {
    	alert("Informe a instituição em que trabalha");
		document.cadastro.id_instituicao.focus();
		return false;
	}

    return true;
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
include_once("../../autentica.inc");

$nome = isset($_REQUEST['nome']) ? $_REQUEST['nome'] : NULL;
$cress = isset($_REQUEST['cress']) ? $_REQUEST['cress'] : NULL;
$telefone = isset($_REQUEST['telefone']) ? $_REQUEST['telefone'] : NULL;
$celular = isset($_REQUEST['celular']) ? $_REQUEST['celular'] : NULL;
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : NULL;
$id_supervisor = isset($_REQUEST['id_supervisor']) ? $_REQUEST['id_supervisor'] : NULL;

if ($cress) {
	if (ctype_digit($cress) == FALSE) echo "Digite somente números.";
}

$sql  = "select inst_super.id as id_inst_super, estagio.id as id_instituicao, instituicao, supervisores.id, supervisores.cress, supervisores.nome, supervisores.email, supervisores.telefone, supervisores.celular, supervisores.endereco, supervisores.bairro, supervisores.municipio, supervisores.cep, escola, ano_formatura, outros_estudos, area_curso, ano_curso ";
$sql .=	" from supervisores "; 
$sql .= " left join inst_super on supervisores.id = inst_super.id_supervisor ";
$sql .= " left join estagio on inst_super.id_instituicao = estagio.id ";

if (!empty($id_supervisor)) {
	$sql .= " where supervisores.id='$id_supervisor'";
} elseif(!empty($cress)) {
	$sql .= " where supervisores.cress='$cress'";
} else {
	die("Número de Cress não foi digitado");
}
// echo $sql . "<br>";

$resultado = $db->Execute($sql);

$quantidade = $resultado->RecordCount();

// $acao = ($quantidade == 0) ? "inserir.php" : "atualizar.php";

// Se esta sendo utilizada como cadastro entao bloquea caso nao tenha cress
if ($sistema_autentica == 0) {
	if ($quantidade == 0) {
		echo "<p>Disculpe, supervisor não cadastrado. Favor, enviar um email para <a href mailto:estagio@ess.ufrj.br>estagio@ess.ufrj.br</a> para que possamos corrigir este problema. Se for possível, acrescente um telefone para contato.<p>";
		exit;
	}
}

// while (!$resultado->EOF) {
	$id_inst_super = $resultado->fields['id_inst_super'];
	$id_instituicao = $resultado->fields['id_instituicao'];
	$instituicao = $resultado->fields['instituicao'];
	$id_supervisor = $resultado->fields['id'];
    $cress = $resultado->fields['cress'];
    $nome = $resultado->fields['nome'];
    $email = $resultado->fields['email'];
    $telefone = $resultado->fields['telefone'];
    $celular = $resultado->fields['celular'];
    $endereco = $resultado->fields['endereco'];
    $bairro = $resultado->fields['bairro'];
    $municipio = $resultado->fields['municipio'];
    $cep = $resultado->fields['cep'];
    $escola = $resultado->fields['escola'];
    $ano_formatura = $resultado->fields['ano_formatura'];
    $outros_estudos = $resultado->fields['outros_estudos'];
    $area_curso = $resultado->fields['area_curso'];
    $ano_curso = $resultado->fields['ano_curso'];
	
//    $resultado->MoveNext();
	// die();
// }

// die();

?>


<form action="atualizar_cadastro.php" name="cadastro" id="cadastro" method="post">

<div align="center">
<table border="1">
<tbody>

<tr>
<td colspan="2" class="rodape">
<b>Dados do supervisor</b>
</td>
</tr>

<tr>
<td width="30%">Nome: </td>
<td width="70%"><input type="text" name="nome" id="nome" maxlength="70" size="50" value="<?php echo $nome; ?>"></td>
</tr>

<tr>
<td>Endereço: </td>
<td><input type="text" name="endereco" id="endereco" maxlength="100" size="50" value="<?php echo $endereco; ?>"></td>
</tr>

<tr>
<td>Bairro: </td>
<td><input type="text" name="bairro" id="bairro" maxlength="30" size="30" value="<?php echo $bairro; ?>"></td>
</tr>

<tr>
<td>Município: </td>
<td><input type="text" name="municipio" id="municipio" maxlength="30" size="30" value="<?php echo $municipio; ?>"></td>
</tr>

<tr>
<td>CEP:</td>
<td><input type="text" name="cep" id="cep" maxlength="9" size="9"  value="<?php echo $cep; ?>" onkeyup="contacarateres();">
<span id="quantidade"></span></td>
</tr>

<tr>
<td>Telefone: </td>
<td>
Código: <input type="text" name="codigo_tel" id="codigo_tel" maxlength="2" size="2" value="21">
<input type="text" name="telefone" id="telefone" maxlength="9" size="9"  value="<?php echo $telefone; ?>"></td>
</tr>

<tr>
<td>Celular: </td>
<td>
Código: <input type="text" name="codigo_cel" id="codigo_cel" maxlength="2" size="2" value="21">
<input type="text" name="celular" id="celular" maxlength="9" size="9" value="<?php echo $celular; ?>"></td>
</tr>

<tr>
<td>E-mail: </td>
<td><input type="text" name="email" id="email" maxlength="50" size="50" value="<?php echo $email; ?>" onBlur="return verifica_email();"></td>
</tr>

<tr>
<td>Escola em que se formou: </td>
<td><input type="text" name="escola" id="escola" maxlength="70" size="30" value="<?php echo $escola; ?>"></td>
</tr>

<tr>
<td>Ano em que se formou: </td>
<td><input type="text" name="ano_formatura" id="ano_formatura" maxlength="4" size="4" value="<?php echo $ano_formatura; ?>"></td>
</tr>

<tr>
<td>No. de registro no CRESS</td>
<td>
<input type="text" name="cress" id="cress" maxlength="15" size="10" value="<?php echo $cress; ?>">
<span style="text-align:right">Região: </span><input type="text" name="regiao" id="regiao" maxlength="2" size="1" value="7">
</td>
</tr>

<tr>
<td>Outros estudos realizados:</td>

<?php
if ($outros_estudos == 'especialização') {
echo "
	<td>
	<input type='radio' name='outros_estudos' id='outros_estudos' value='especialização' checked>
	Especialização
	<input type='radio' name='outros_estudos' id='outros_estudos' value='mestrado'>
	Mestrado
	<input type='radio' name='outros_estudos' id='outros_estudos' value='doutorado'>
	Doutorado
	</td>
";
}
?>

<?php 
if ($outros_estudos == 'mestrado') {
echo "
	<td>
	<input type='radio' name='outros_estudos' id='outros_estudos' value='especialização'>
	Especialização
	<input type='radio' name='outros_estudos' id='outros_estudos' value='mestrado' checked>
	Mestrado
	<input type='radio' name='outros_estudos' id='outros_estudos' value='doutorado'>
	Doutorado
	</td>
";
}
?>

<?php
if ($outros_estudos == 'doutorado') {
echo "
	<td>
	<input type='radio' name='outros_estudos' id='outros_estudos' value='especialização'>
	Especialização
	<input type='radio' name='outros_estudos' id='outros_estudos' value='mestrado'>
	Mestrado
	<input type='radio' name='outros_estudos' id='outros_estudos' value='doutorado' checked>
	Doutorado
	</td>
";
}
?>

<?php
if (empty($outros_estudos)) {
echo "
	<td>
	<input type='radio' name='outros_estudos' id='outros_estudos' value='especialização'>
	Especialização
	<input type='radio' name='outros_estudos' id='outros_estudos' value='mestrado'>
	Mestrado
	<input type='radio' name='outros_estudos' id='outros_estudos' value='doutorado'>
	Doutorado
	</td>
";
}
?>

</tr>

<tr>
<td>Área do curso anteriormente mencionado</td>
<td><input type="text" name="area_curso" id="area_curso" maxlength="40" size="30" value="<?php echo $area_curso; ?>"></td>
</tr>

<tr>
<td>Ano de conclusão do curso (deixar em branco se ainda não foi concluído)</td>
<td><input type="text" name="ano_curso" id="ano_curso" maxlength="4" size="4" value="<?php echo $ano_curso; ?>"></td>
</tr>

<tr>
<td>Selecione instituição</td>
<td>
<select name='id_instituicao' id='id_instituicao'>
<option value='<?php echo $id_instituicao; ?>'><?php echo $instituicao; ?></option>
<?php

$sql = "select estagio.id, estagio.instituicao from estagio order by instituicao";	
$resultado = $db->Execute($sql);
while (!$resultado->EOF) {
	$id = $resultado->fields['id'];
	$instituicao = $resultado->fields['instituicao'];
	
	echo "
	<option value='$id'>$instituicao</option>
	";
	
	$resultado->MoveNext();
}

?>
</select>
</td>
</tr>

<tr>
<td colspan="2" class="coluna_centralizada">
<input type="hidden" name="id_supervisor" value="<?php echo $id_supervisor; ?>">
<input type="hidden" name="id_inst_super" value="<?php echo $id_inst_super; ?>">
<?php 
if ($sistema_autentica == 0) {
	echo "
	<input type='submit' name='enviar' value='Confirmar' onClick='return confirma()'>
	";
} elseif ($sistema_autentica == 1) {
	echo "
	<input type='submit' name='enviar' value='Confirmar'>
	";
}
?>
<input type="reset" name="limpar" value="Limpar">
</td>
</tr>

</tbody>
</table>
</div>

</form>

</body>

</html>
