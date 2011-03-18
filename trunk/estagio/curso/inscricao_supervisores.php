<?php require_once("ajaxInstituicao.php"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">
    <title>Inscri&ccedil;&atilde;o para curso de supervisores</title>
	<script type="text/javascript" src="../lib/jquery.js"></script>
	<script type="text/javascript" src="../lib/jquery.maskedinput-1.2.1.pack.js"></script>
	<script type="text/javascript">
	$(function() {
		 $("#cpf").mask("999999999-99");
		 $("#cep").mask("99999-999");
		 $("#telefone").mask("9999.9999");
 		 $("#celular").mask("9999.9999");
		 $("#ano_formatura").mask("9999");
 		 $("#ano_curso").mask("9999");
		 $("#instituicao_cep").mask("99999-999");
	});
	</script>

<?php $xajax->printJavascript(); ?>
<script language="JavaScript" type="text/javascript">
<!--
function confirma() {
    var nome, cpf, endereco, bairro, municipio, cep, escola, ano_formatura, email, cress, instituicao;

    nome=document.getElementById('nome').value;
    cpf=document.getElementById('cpf').value;
    endereco=document.getElementById('endereco').value;
    bairro=document.getElementById('bairro').value;
    municipio=document.getElementById('municipio').value;
    cep=document.getElementById('cep').value;
    escola=document.getElementById('escola').value;
    ano_formatura=document.getElementById('ano_formatura').value;
    email=document.getElementById('email').value;
    cress=document.getElementById('cress').cress.value;
    instituicao=document.getElementById('instituicaoNova').value;

    if(nome=="") {
    	alert("Informe o seu nome");
        document.getElementById('nome').focus();
		return false;
	}

    if(cpf=="") {
    	alert("Informe o seu CPF");
		document.inscricao.cpf.focus();
		return false;
	}

    if(endereco=="") {
    	alert("Informe o seu endereço");
        document.getElementById('endereco').focus();
		return false;
	}

    if(bairro=="") {
    	alert("Informe o bairro da sua residencia");
        document.getElementById('bairro').focus();
		return false;
	}

    if(municipio=="") {
    	alert("Informe o municipio da sua residencia");
        document.getElementById('municipio').focus();
	return false;
	}

    if(cep=="") {
    	alert("Informe o CEP do seu endereço");
        document.getElementById('cep').focus();
	return false;
	}

    if(email=="") {
    	alert("Favor informe o seu e-mail");
        document.getElementById('email').focus();
	return false;
	}

    if(escola=="") {
    	alert("Favor informe a escola na que se formou");
        document.getElementById('escola').focus();
	return false;
	}

    if(ano_formatura=="") {
    	alert("Favor informe o ano da sua formatura");
        document.getElementById('ano_formatura').focus();
	return false;
	}

    if(cress=="") {
    	alert("Favor informe o seu número de registro no CRESS 7a. região");
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
    if(nome=="") {
	alert("Informe o seu nome");
	document.inscricao.nome.focus();
	return false;
} else {
	return true;
    }
}

function verifica_cpf() {
    var cpf;
    cpf=document.inscricao.cpf.value;
    if(cpf=="") {
	alert("Informe o seu CPF");
	document.inscricao.cpf.focus();
	return false;
} else {
	return true;
    }
}

function verifica_email() {
    var email;
    email=document.inscricao.email.value;
    if(email=="") {
	alert("É importante o e-mail para nossa comunicação");
	document.inscricao.email.focus();
	return false;
} else {
	return true;
    }
}

function verifica_cress() {
    var cress;
    cress=document.inscricao.cress.value;
    if(cress=="") {
	alert("Informe o seu número de registro no CRESS 7a. região");
	document.inscricao.cress.focus();
	return false;
} else {
	return true;
    }
}

function verifica_instituicao() {
    var instituicao;
    instituicao=document.inscricao.instituicaoNova.value;
    if(instituicao=="") {
	alert("Informe a instituição na que trabalha");
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
    
    xajax_verInstituicao(id_instituicao);
    // alert(id_instituicao);
    return true;
}
//-->
</script>
<style type="text/css">
@import url(formulario_curso.css);
</style>
</head>
<body>

<?php

include_once("../setup.php");

$turma = TURMA;

// echo "Hoje " . date('m/d/Y'). "<br>";
// echo "Encerramento " . ENCERRAMENTO . "<br>";
// O comando strtotime somente funciona com o formato de data USA
$data_encerramento = strtotime(ENCERRAMENTO);
$data_hoje = strtotime(date('m/d/Y'));

$sql  = "select count(*) as quantidade ";
$sql .= " from curso_inscricao_supervisor ";
$sql .= " where curso_turma=$turma";
// echo $sql . "<br>";
$res_sql = $db->Execute($sql);
if($res_sql === false) die ("Nao foi possivel consultar a tabela curso_inscricao_supervisor");
$quantidade = $res_sql->fields['quantidade'];

// var_dump($data_encerramento);
// var_dump($data_hoje);

if($data_hoje > $data_encerramento) {
    echo "<h2>Inscri&ccedil;&otilde;es encerradas</h2>";
    // echo "<h2>Inscri&ccedil;&otilde;es para 2009-1 a partir do 02/03/2009</h2>";
    // include("doc/selecionados2008.html");
    exit;
}

?>

<form action="inscricao_curso.php" name="inscricao" method="post">
<h1>Formul&aacute;rio de inscri&ccedil;&atilde;o para o <?php echo $turma; ?>o. curso de
capacita&ccedil;&atilde;o profissional para supervisores:
</h1>

<h2>"Desafios para interven&ccedil;&atilde;o profissional do assistente social"</h2>

<div align="center">
<table border="1">
<tbody>

<tr>
<td colspan="2" class="coluna_centralizada">
Quantidade de inscri&ccedil;&otilde;es realizadas: <?php echo $quantidade; ?>
</td>
</tr>

<tr>
<td colspan="2" class="rodape">
<b>Dados do supervisor</b>
</td>
</tr>

<tr>
<td width="30%">Nome*: </td>
<td width="70%">
<input type="text" name="nome" id="nome" value="<?php echo $nome; ?>" maxlength="70" size="45" onBlur="return verifica_nome();">
CPF*: <input type="text" name="cpf" id="cpf" value="<?php echo $cpf; ?>" maxlength="12" size="12" onBlur="return verifica_cpf();"> 
</td>
</tr>

<tr>
<td>Endere&ccedil;o*: </td>
<td><input type="text" name="endereco" id="endereco" value="<?php echo $endereco; ?>" maxlength="100" size="50"></td>
</tr>

<tr>
<td>Bairro*: </td>
<td>
<input type="text" name="bairro" id="bairro" value="<?php echo $bairro; ?>" maxlength="30" size="18">
Munic&iacute;pio*:
<input type="text" name="municipio" id="municipio"  value="<?php echo $municipio; ?>" maxlength="30" size="18">
CEP*:
<input type="text" name="cep" id="cep" value="<?php echo $cep; ?>" maxlength="9" size="9">
</td>
</tr>

<tr>
<td>Telefone: </td>
<td>
C&oacute;digo: <input type="text" name="codigo_tel" id="codigo_tel" maxlength="2" size="2" value="21">
<input type="text" name="telefone" id="telefone" value="<?php echo $telefone; ?>" maxlength="9" size="9">
Celular: 
<input type="text" name="codigo_cel" id="codigo_cel" maxlength="2" size="2" value="21">
<input type="text" name="celular" id="celular" value="<?php echo $celular; ?>" maxlength="9" size="9">
</td>
</tr>

<tr>
<td>E-mail*: </td>
<td><input type="text" name="email" id="email" value="<?php echo $email; ?>" maxlength="50" size="50" onBlur="return verifica_email();"></td>
</tr>

<tr>
<td>Escola na qual se formou*: </td>
<td><input type="text" name="escola" id="escola" value="<?php echo $escola; ?>" maxlength="70" size="60"></td>
</tr>

<tr>
<td>Ano em que se formou*: </td>
<td><input type="text" name="ano_formatura" id="ano_formatura" value="<?php echo $ano_formatura; ?>" maxlength="4" size="4"></td>
</tr>

<tr>
<td>No. de registro no CRESS*</td>
<td>
<label for="cress">Digite o n&uacute;mero 0 se ainda n&atilde;o tem n&uacute;mero do CRESS</label>
<input type="text" name="cress" id="cress" value="<?php echo $cress; ?>" maxlength="15" size="10" onBlur="return verifica_cress();">
	   <span style="text-align:right">Regi&atilde;o: </span><input type="text" name="regiao" id="regiao" maxlength="2" size="1" value="7">
</td>
</tr>

<tr>
<td>Outros estudos realizados:</td>
<td>
<input type="radio" name="outros_estudos" id="outros_estudos" value="especializacão">
Especializa&ccedil;&atilde;o
<input type="radio" name="outros_estudos" id="outros_estudos" value="mestrado">
Mestrado
<input type="radio" name="outros_estudos" id="outros_estudos" value="doutorado">
Doutorado
</td>
</tr>

<tr>
<td>&Aacute;rea em que foi realizado o curso anteriormente mencionado</td>
<td><input type="text" name="area_curso" id="area_curso" value="<?php echo $area_curso; ?>" maxlength="40" size="30"></td>
</tr>

<tr>
<td>Ano em que foi conclu&iacute;do o curso</td>
<td><input type="text" name="ano_curso" id="ano_curso" value="<?php echo $ano_curso; ?>" maxlength="4" size="4"></td>
</tr>

<tr>
<td colspan="2" class="rodape">
<b>Dados da institui&ccedil;&atilde;o</b></td>
</tr>

<?php
$sql  = "select id, instituicao ";
$sql .= " from estagio ";
// $sql .= " group by instituicao ";
$sql .= " order by instituicao";
$resultado = $db->Execute($sql);
if($resultado_=== false) die ("Nao foi possivel consultar a tabela estagio");
?>
<tr>
<td>Selecione a institui&ccedil;&atilde;o ou programa*: </td>
<td>
<select name="id_instituicao" id="id_instituicao" size="1" onChange="return verInstituicao();">
<option value=0>Selecione a institui&ccedil;&atilde;o aqui, caso contr&aacute;rio, cadastre uma nova institui&ccedil;&atilde;o</option>
<?php
while (!$resultado->EOF) {
    $id_instituicao = $resultado->fields['id'];
    $instituicao = $resultado->fields['instituicao'];
    echo "
    <option value=$id_instituicao>$instituicao</option>
    ";
    $resultado->MoveNext();
}
?>
</select>
</td>
</tr>

<input type="hidden" name="num_instituicao" id="num_instituicao">

<tr>
<td>Nome da institui&ccedil;&atilde;o ou programa*: </td>
<td>
<label for="instituicaoNova">Digite "N&atilde;o trabalha" se n&atilde;o est&aacute;a trabalhado</label>
<input type="text" maxlength="75" size="75" name="instituicaoNova" id="instituicaoNova" onBlur="return verifica_instituicao();">
   
</td>
</tr>

<tr>
<td>Endere&ccedil;o: </td>
<td><input type="text" maxlength="105" size="50" name="instituicao_endereco" id="instituicao_endereco"></td>
</tr>

<tr>
<td>Bairro: </td>
<td><input type="text" maxlength="30" size="30" name="instituicao_bairro" id="instituicao_bairro"></td>
</tr>

<tr>
<td>Munic&iacute;pio: </td>
<td><input type="text" maxlength="30" size="30" name="instituicao_municipio" id="instituicao_municipio"></td>
</tr>

<tr>
<td>CEP: </td>
<td><input type="text" maxlength="9" size="10" name="instituicao_cep" id="instituicao_cep"></td>
</tr>

<tr>
<td>Telefone: </td>
<td><input type="text" maxlength="50" size="50" name="instituicao_telefone" id="instituicao_telefone"></td>
</tr>

<tr>
<td>Fax: </td>
<td><input type="text" maxlength="15" size="15" name="instituicao_fax" id="instituicao_fax"></td>
</tr>

<tr>
		   <td>Benef&iacute;cios do est&aacute;gio</td>
<td>
		   <label for="instituicao_beneficio">Benef&iacute;cios econ&oacute;micos que o estagi&aacute;rio recebe (ex. bolsa, vale transporte, etc.)</label>
<input type="text" maxlength="50" size="50" name="instituicao_beneficio" id="instituicao_beneficio"></td>
</tr>

<tr>
<td>Tem est&aacute;gio no final de semana: </td>
<td>
N&atilde;o:<input type="radio" name="fim_de_semana" id="fim_de_semana" value="0" checked>
Sim:<input type="radio" name="fim_de_semana" id="fim_de_semana" value="1">
Parcialmente:<input type="radio" name="fim_de_semana" id="fim_de_semana" value="2">
</td>
</tr>

<tr>
<td colspan="2" class="coluna_centralizada">
<input type="submit" name="enviar" value="Enviar" onClick="return confirma();">
<input type="reset" name="limpar" value="Limpar">
</td>
</tr>

</tbody>
</table>
</div>

</form>

</body>
</html>
