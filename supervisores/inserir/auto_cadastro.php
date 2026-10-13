<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">
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
require_once(__DIR__ . "/../../libphp/models.php");
include_once("../../autentica.inc");

// Initialize and cast all variables to prevent undefined warnings
$nome = isset($_REQUEST['nome']) ? $_REQUEST['nome'] : '';
$cress = isset($_REQUEST['cress']) ? $_REQUEST['cress'] : '';
$telefone = isset($_REQUEST['telefone']) ? $_REQUEST['telefone'] : '';
$celular = isset($_REQUEST['celular']) ? $_REQUEST['celular'] : '';
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
$supervisor_id = isset($_REQUEST['supervisor_id']) ? (int)$_REQUEST['supervisor_id'] : 0;

// Initialize variables to prevent undefined warnings
$inst_super_id = 0;
$instituicao_id = 0;
$instituicao = '';
$endereco = '';
$bairro = '';
$municipio = '';
$cep = '';
$escola = '';
$ano_formatura = '';
$outros_estudos = '';
$area_curso = '';
$ano_curso = '';

if ($cress) {
	if (ctype_digit($cress) == FALSE) echo "Digite somente números.";
}

$sql  = "select inst_super.id as id_inst_super, instituicoes.id as instituicao_id, instituicao, supervisores.id, supervisores.cress, supervisores.nome, supervisores.email, supervisores.telefone, supervisores.celular, supervisores.endereco, supervisores.bairro, supervisores.municipio, supervisores.cep, escola, ano_formatura, outros_estudos, area_curso, ano_curso ";
$sql .=	" from supervisores "; 
$sql .= " left join inst_super on supervisores.id = inst_super.supervisor_id ";
$sql .= " left join instituicoes on inst_super.instituicao_id = instituicoes.id ";

// Use parameterized queries to prevent SQL injection
$params = array();
if (!empty($supervisor_id)) {
	$sql .= " where supervisores.id=?";
	$params[] = $supervisor_id;
} elseif(!empty($cress)) {
	$sql .= " where supervisores.cress=?";
	$params[] = $cress;
} else {
	die("Número de Cress não foi digitado");
}

// Use model query method instead of raw $db->Execute
$resultado = Supervisor::query($sql, $params);
if ($resultado === false) die ("Não foi possível consultar a tabela supervisores");

$quantidade = $resultado->RecordCount();

// Se esta sendo utilizada como cadastro entao bloquea caso nao tenha cress
if ($sistema_autentica == 0) {
	if ($quantidade == 0) {
		echo "<p>Disculpe, supervisor não cadastrado. Favor, enviar um email para <a href mailto:estagio@ess.ufrj.br>estagio@ess.ufrj.br</a> para que possamos corrigir este problema. Se for possível, acrescente um telefone para contato.<p>";
		exit;
	}
}

// Replace legacy EOF loop with modern foreach loop (ADODB_Model compatible)
foreach ($resultado as $item) {
	$inst_super_id = isset($item->id_inst_super) ? (int)$item->id_inst_super : 0;
	$instituicao_id = isset($item->instituicao_id) ? (int)$item->instituicao_id : 0;
	$instituicao = isset($item->instituicao) ? $item->instituicao : '';
	$supervisor_id = isset($item->id) ? (int)$item->id : 0;
    $cress = isset($item->cress) ? $item->cress : '';
    $nome = isset($item->nome) ? $item->nome : '';
    $email = isset($item->email) ? $item->email : '';
    $telefone = isset($item->telefone) ? $item->telefone : '';
    $celular = isset($item->celular) ? $item->celular : '';
    $endereco = isset($item->endereco) ? $item->endereco : '';
    $bairro = isset($item->bairro) ? $item->bairro : '';
    $municipio = isset($item->municipio) ? $item->municipio : '';
    $cep = isset($item->cep) ? $item->cep : '';
    $escola = isset($item->escola) ? $item->escola : '';
    $ano_formatura = isset($item->ano_formatura) ? $item->ano_formatura : '';
    $outros_estudos = isset($item->outros_estudos) ? $item->outros_estudos : '';
    $area_curso = isset($item->area_curso) ? $item->area_curso : '';
    $ano_curso = isset($item->ano_curso) ? $item->ano_curso : '';
}

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
<select name='instituicao_id' id='instituicao_id'>
<option value='<?php echo $instituicao_id; ?>'><?php echo $instituicao; ?></option>
<?php

$sql = "select instituicoes.id, instituicoes.instituicao from instituicoes order by instituicao";	
$resultado = Instituicao::query($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela instituicoes");
foreach ($resultado as $item) {
	$id = (int)$item->id;
	$instituicao = $item->instituicao;
	
	echo "
	<option value='$id'>$instituicao</option>
	";
}

?>
</select>
</td>
</tr>

<tr>
<td colspan="2" class="coluna_centralizada">
<input type="hidden" name="supervisor_id" value="<?php echo $supervisor_id; ?>">
<input type="hidden" name="inst_super_id" value="<?php echo $inst_super_id; ?>">
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