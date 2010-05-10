<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    <title>Dados de inscri&ccedil;&atilde;o para curso de supervisores</title>
	<!-- Autocompletar //-->
	<script type="text/javascript" src="../lib/jquery.js"></script>
	<script type='text/javascript' src='../lib/jquery.autocomplete.js'></script>
	<link rel="stylesheet" type="text/css" href="../lib/jquery.autocomplete.css" />
	<script type="text/javascript">
		
	function selectItem(li) {
		if (li.extra) {
			// alert(" " + li.extra[0] + " selecionado.");
		}
	}
	
	function formatItem(row) {
		return row[0] + "<br><i>" + row[1] + "</i>";
	}
	
	$(document).ready(function() {
		$("#nome").autocomplete("supervisores.php", { minChars:3, matchSubset:1, matchContains:1, cacheLength:10, onItemSelect:selectItem, formatItem:formatItem, selectOnly:1 });
	});
	
	</script>

	<script src="../lib/jquery.maskedinput-1.2.1.pack.js" type="text/javascript"></script>
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
    	alert("Favor informar o seu número de registro no CRESS 7a. região");
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
	alert("Você precisa informar o seu nome");
	document.inscricao.nome.focus();
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
	alert("Você precisa informar o número de registro no CRESS 7a. região");
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

//-->
</script>
<style type="text/css">
@import url(formulario_curso.css);
</style>
</head>
<body>

<a href='javascript:history.back();'>Voltar</a>

<?php

include_once("../db.inc");
include_once("../setup.php");
include_once("../autoriza.inc");

$id_supervisor = isset($_REQUEST['id_supervisor']) ? $_REQUEST['id_supervisor'] : NULL;
$submit = isset($_REQUEST['submit']) ? $_REQUEST['submit'] : NULL;
$flag = isset($_REQUEST['flag']) ? $_REQUEST['flag'] : NULL;

$nome = isset($_REQUEST['nome']) ? $_REQUEST['nome'] : NULL;
$cpf = isset($_REQUEST['cpf']) ? $_REQUEST['cpf'] : NULL;
$endereco = isset($_REQUEST['endereco']) ? $_REQUEST['endereco'] : NULL;
$municipio = isset($_REQUEST['municipio']) ? $_REQUEST['municipio'] : NULL;
$bairro = isset($_REQUEST['bairro']) ? $_REQUEST['bairro'] : NULL;
$cep = isset($_REQUEST['cep']) ? $_REQUEST['cep'] : NULL;
$codigo_tel = isset($_REQUEST['codigo_tel']) ? $_REQUEST['codigo_tel'] : NULL;
$telefone = isset($_REQUEST['telefone']) ? $_REQUEST['telefone'] : NULL;
$codigo_cel = isset($_REQUEST['codigo_cel']) ? $_REQUEST['codigo_cel'] : NULL;
$celular = isset($_REQUEST['celular']) ? $_REQUEST['celular'] : NULL;
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : NULL;
$regiao = isset($_REQUEST['regiao']) ? $_REQUEST['regiao'] : NULL;
$cress = isset($_REQUEST['cress']) ? $_REQUEST['cress'] : NULL;
$escola = isset($_REQUEST['escola']) ? $_REQUEST['escola'] : NULL;
$ano_formatura = isset($_REQUEST['ano_formatura']) ? $_REQUEST['ano_formatura'] : NULL;
$outros_estudos = isset($_REQUEST['outros_estudos']) ? $_REQUEST['outros_estudos'] : NULL;
$ano_curso = isset($_REQUEST['ano_curso']) ? $_REQUEST['ano_curso'] : NULL;
$area_curso = isset($_REQUEST['area_curso']) ? $_REQUEST['area_curso'] : NULL;
$num_inscricao = isset($_REQUEST['num_inscricao']) ? $_REQUEST['num_inscricao'] : NULL;
$curso_turma = isset($_REQUEST['curso_turma']) ? $_REQUEST['curso_turma'] : NULL;
$selecao = isset($_REQUEST['selecao']) ? $_REQUEST['selecao'] : NULL;

// Para alterar entre as diferentes visoes
$flag++;

if ($flag == 1) {
	$botao = "Alterar dados";
} elseif ($flag == 2) {
	$botao = "Atualizar";
} elseif ($flag == 3) {

	$sql = "update curso_inscricao_supervisor set nome='$nome', cpf='$cpf', endereco='$endereco', municipio='$municipio', bairro='$bairro', cep='$cep', codigo_tel='$codigo_tel', telefone='$telefone', codigo_cel='$codigo_cel' ,celular='$celular', email='$email', escola='$escola', ano_formatura='$ano_formatura', cress='$cress', regiao='$regiao', outros_estudos='$outros_estudos', area_curso='$area_curso', ano_curso='$ano_curso', selecao='$selecao' where id='$id_supervisor'";
	// echo $sql . "<br>";
	$res_atualiza = $db->Execute($sql);
	if($res_atualiza === false) die ("Nao foi possivel atualizar a tabela curso_inscricao_supervisor");

	unset($submit);
	$flag = 1;
	$botao = "Alterar dados";
}

$sql  = "select nome, cpf, curso_inscricao_supervisor.endereco, curso_inscricao_supervisor.bairro, curso_inscricao_supervisor.municipio, curso_inscricao_supervisor.cep, codigo_tel, curso_inscricao_supervisor.telefone, codigo_cel, celular, email, escola, ano_formatura, cress, regiao, outros_estudos, area_curso, ano_curso, num_inscricao, curso_turma, selecao, ";
$sql .= " id_estagio, curso_inscricao_instituicao.id as instituicao_id, instituicao ";
$sql .= " from curso_inscricao_supervisor ";
$sql .= " left join curso_inst_super on curso_inscricao_supervisor.id = curso_inst_super.id_supervisor ";
$sql .= " left join curso_inscricao_instituicao on curso_inst_super.id_instituicao = curso_inscricao_instituicao.id ";
$sql .= " where curso_inscricao_supervisor.id='$id_supervisor'";
// echo $sql . "<br>";

$res_sql = $db->Execute($sql);
if($res_sql === false) die ("Nao foi possivel consultar a tabela curso_inscricao_supervisor");

while (!$res_sql->EOF) {
	$nome = $res_sql->fields['nome'];
	$cpf = $res_sql->fields['cpf'];
    $endereco = $res_sql->fields['endereco'];
	$bairro = $res_sql->fields['bairro'];
	$municipio = $res_sql->fields['municipio'];
	$cep = $res_sql->fields['cep'];
	$codigo_tel = $res_sql->fields['codigo_tel'];
	$telefone = $res_sql->fields['telefone'];
	$codigo_cel = $res_sql->fields['codigo_cel'];
	$celular = $res_sql->fields['celular'];
	$email = $res_sql->fields['email'];
	$escola = $res_sql->fields['escola'];		
	$ano_formatura = $res_sql->fields['ano_formatura'];
	$regiao = $res_sql->fields['regiao'];
	$cress = $res_sql->fields['cress'];
	$outros_estudos = $res_sql->fields['outros_estudos'];	
	$area_curso = $res_sql->fields['area_curso'];	
	$ano_curso = $res_sql->fields['ano_curso'];
	$num_inscricao = $res_sql->fields['num_inscricao'];
	$curso_turma = $res_sql->fields['curso_turma'];
	$selecao = $res_sql->fields['selecao'];

	$estagio_id = $res_sql->fields['id_estagio'];
	$instituicao_id = $res_sql->fields['instituicao_id'];
	$instituicao = $res_sql->fields['instituicao'];
	
	$res_sql->MoveNext();
	}

?>

<form action="#" name="ver_cada_supervisor" method="post">

<div align="center">
<table border="1">
<tbody>

<tr>
<td colspan="2" class="rodape">
<b>Dados de inscrição para o curso de supervisores</b>
</td>
</tr>

<tr>
<td width="30%">Nome: </td>
<td width="70%">
<?php
if ($submit) {
	echo "
	<input type='text' name='nome' id='nome' value='$nome' maxlength='70' size='50' onBlur='return verifica_nome();'>
	";
} else {
	echo $nome;
}
?>
</td>
</tr>

<?php
if ($sistema_autentica == 1) {
?>

    <tr><td>CPF: </td>
    <td>
    <?php
    if ($submit) {
        echo "
     	<input type='text' name='cpf' id='cpf' value='$cpf' maxlength='12' size='12'>
        ";
    } else {
        echo $cpf;
    }
    ?>
    </td>
    </tr>

	<tr>
	<td>Endereço: </td>
	<td>
	<?php
	if ($submit) {
		echo "
		<input type='text' name='endereco' id='endereco' value='$endereco' maxlength='100' size='40'>
		";
	} else {
		echo $endereco;
	}
	?>

	</td>
	</tr>

	<tr>
	<td>Bairro: </td>
	<td>
	<?php
	if ($submit) {
		echo "
		<input type='text' name='bairro' id='bairro' value='$bairro' maxlength='30' size='30'>
		";
	} else {
		echo $bairro;
	}
	?>
	</td>
	</tr>
	
	<tr>
	<td>Município: </td>
	<td>
	<?php
	if ($submit) {
		echo "
		<input type='text' name='municipio' id='municipio' value='$municipio' maxlength='30' size='30'>
		";
	} else {
		echo $municipio;
	}
	?>
	</td>
	</tr>
	
	<tr>
	<td>CEP:</td>
	<td>
	<?php
	if ($submit) {
		echo "
		<input type='text' name='cep' id='cep' value='$cep' maxlength='9' size='9' onkeyup='contacarateres();'>
		<span id='quantidade'></span>
		";
	} else {
		echo $cep;
	}
	?>
	</td>
	</tr>

	<tr>
	<td>Telefone: </td>
	<td>
	<?php
	if ($submit) {
		echo "
		Código: <input type='text' name='codigo_tel' id='codigo_tel' maxlength='2' size='2' value='$codigo_tel'>
		<input type='text' name='telefone' id='telefone' value='$telefone' maxlength='9' size='9'>
		";
	} else {
		echo '(' . $codigo_tel . ')' . $telefone;
	}
	?>
	</td>
	</tr>
	
	<tr>
	<td>Celular: </td>
	<td>
	<?php
	if ($submit) {
		echo "
		Código: <input type='text' name='codigo_cel' id='codigo_cel' maxlength='2' size='2' value='$codigo_cel'>
		<input type='text' name='celular' id='celular' value='$celular' maxlength='9' size='9'>
		";
	} else {
		echo '(' . $codigo_cel . ')' . $celular;
	}
	?>
	</td>
	</tr>
	
	<tr>
	<td>E-mail: </td>
	<td>
	<?php
	if ($submit) {
		echo "
		<input type='text' name='email' id='email' value='$email' maxlength='50' size='50' onBlur='return verifica_email();'>
		";
	} else {
		echo $email;
	}
	?>
	</td>
	</tr>

<?php
}
?>

<tr>
<td>Escola na qual se formou: </td>
<td>
<?php
if ($submit) {
	echo "
	<input type='text' name='escola' id='escola' value='$escola' maxlength='70' size='60'>
	";
} else {
	echo $escola;
}
?>
</td>
</tr>

<tr>
<td>Ano em que se formou: </td>
<td>
<?php
if ($submit) {
	echo "
	<input type='text' name='ano_formatura' id='ano_formatura' value='$ano_formatura' maxlength='4' size='4'>
	";
} else {
	echo $ano_formatura;
}
?>
</td>
</tr>

<tr>
<td>No. de registro no CRESS</td>
<td>
<?php
if ($submit) {
	echo "
	<input type='text' name='cress' id='cress' value='$cress' maxlength='6' size='6' onBlur='return verifica_cress();'>
	<span style='text-align:right'>Região: </span><input type='text' name='regiao' id='regiao' maxlength='2' size='1' value='$regiao'>
	";
} else {
	echo $cress;
}
?>
</td>
</tr>

<tr>
<td>Outros estudos realizados:</td>

<?php
if ($outros_estudos == 'especialização') {
	echo "
		<td>Especialização:
		<input type=\"radio\" name=\"outros_estudos\" id=\"outros_estudos\" value=\"especialização\" checked>
		Mestrado:
		<input type=\"radio\" name=\"outros_estudos\" id=\"outros_estudos\" value=\"mestrado\">
		Doutorado
		<input type=\"radio\" name=\"outros_estudos\" id=\"outros_estudos\" value=\"doutorado\">
		</td>
		";
} elseif ($outros_estudos == 'mestrado') {
	echo "
		<td>Especialização:
		<input type=\"radio\" name=\"outros_estudos\" id=\"outros_estudos\" value=\"especialização\">
		Mestrado:
		<input type=\"radio\" name=\"outros_estudos\" id=\"outros_estudos\" value=\"mestrado\" checked>
		Doutorado
		<input type=\"radio\" name=\"outros_estudos\" id=\"outros_estudos\" value=\"doutorado\">
		</td>
		";
} elseif ($outros_estudos == 'doutorado') {
	echo "
		<td>Especialização:
		<input type=\"radio\" name=\"outros_estudos\" id=\"outros_estudos\" value=\"especialização\">
		Mestrado:
		<input type=\"radio\" name=\"outros_estudos\" id=\"outros_estudos\" value=\"mestrado\">
		Doutorado
		<input type=\"radio\" name=\"outros_estudos\" id=\"outros_estudos\" value=\"doutorado\" checked>
		</td>
		";
} else {
	echo "
		<td>Especialização:
		<input type=\"radio\" name=\"outros_estudos\" id=\"outros_estudos\" value=\"especialização\">
		Mestrado:
		<input type=\"radio\" name=\"outros_estudos\" id=\"outros_estudos\" value=\"mestrado\">
		Doutorado
		<input type=\"radio\" name=\"outros_estudos\" id=\"outros_estudos\" value=\"doutorado\">
		</td>
		";
};

?>

</tr>

<tr>
<td>Área em que foi realizado o curso anteriormente mencionado</td>
<td>
<?php
if ($submit) {
	echo "
	<input type='text' name='area_curso' id='area_curso' value='$area_curso' maxlength='40' size='40'>
	";
} else {
	echo $area_curso;
}
?>
</td>
</tr>

<tr>
<td>Ano em que foi concluí­do o curso</td>
<td>
<?php
if ($submit) {
	echo "
	<input type='text' name='ano_curso' id='ano_curso' value='$ano_curso' maxlength='4' size='4'>
	";
} else {
	echo $ano_curso;
}
?>
</td>
</tr>

<tr>
<td>Dados do curso:</td>
<td>
<?php 
echo 'Número de inscrição: ' . $num_inscricao . ' Turma: ' . $curso_turma;
?>
</td>
</tr>

<tr>
<td>Seleção (0 = não; 1 = sim; 2 = espera)</td>
<td>
<?php 
// echo $submit ." " . $selecao;
if ($submit) {
	if ($selacao == '0') {
		echo "
	       <input type='radio' name='selecao' id='selecao' value='0' checked>Não
	       <input type='radio' name='selecao' id='selecao' value='1'>Sim
               <input type='radio' name='selecao' id='selecao' value='2'>Espera
		";
	} elseif ($selecao == '1') {
		echo "
		<input type='radio' name='selecao' id='selecao' value='0'>Não
		<input type='radio' name='selecao' id='selecao' value='1' checked>Sim
                <input type='radio' name='selecao' id='selecao' value='2'>Espera
		";
	} elseif ($selecao == '2') {
		echo "
		<input type='radio' name='selecao' id='selecao' value='0'>Não
		<input type='radio' name='selecao' id='selecao' value='1'>Sim
                <input type='radio' name='selecao' id='selecao' value='2' checked>Espera
		";

	} else {
		echo "
		<input type='radio' name='selecao' id='selecao' value='0' checked>Não
		<input type='radio' name='selecao' id='selecao' value='1'>Sim
                <input type='radio' name='selecao' id='selecao' value='2'>Espera
		";
	}
} else {
	echo $selecao;
}
?>
</td>
</tr>

<tr>
<td>Trabalho:</td>
<td>
<?php if ($estagio_id <> 0) {; ?>
<a href="../instituicoes/exibir/ver_cada.php?id_instituicao=<?php echo $estagio_id; ?>"><?php echo $instituicao; ?></a>
<?php } else { 
echo $instituicao; } ?>
</td>
</tr>

<?php
if ($sistema_autentica == 1) {
?>
	<tr>
	<td colspan='2' style='text-align:center'>
		<input type='hidden' name='flag' value='<?php echo $flag; ?>'>
		<input type='hidden' name='id_supervisor' value='<?php echo $id_supervisor; ?>'>
		<input type='submit' name='submit' value='<?php echo $botao; ?>'>
	</td>
	</tr>
<?php
}
?>

</tbody>
</table>
</div>

</form>

</body>
</html>
