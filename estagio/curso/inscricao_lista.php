<?php
/*
 * Created on 07/07/2005
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once("../setup.php");
include_once("../autoriza.inc");

// echo "Autentica " . $sistema_autentica;

$turma = isset($_GET['turma']) ? $turma = $_REQUEST['turma'] : TURMA;
$ordem = isset($_GET['ordem']) ? $ordem = $_REQUEST['ordem'] : ordem;
$selecao = isset($_GET['selecao']) ? $selecao = $_REQUEST['selecao'] : NULL;

// Elimino a variavel se esta em branco
if ($selecao == '') unset($selecao);

$inscricaoRealizada = $_REQUEST['num_inscricao'];

$sql  = "select s.id, s.num_inscricao, s.cress, s.nome, s.email, s.codigo_tel, s.telefone, s.codigo_cel, s.celular, s.selecao, i.id as id_instituicao, i.instituicao, i.id_estagio ";
$sql .=	" from curso_inscricao_supervisor as s ";
$sql .= " inner join curso_inst_super as j on s.id = j.id_supervisor ";
$sql .= " inner join curso_inscricao_instituicao as i ";
$sql .= " on j.id_instituicao = i.id ";
if (!empty($turma) or $turma != 0) $sql .= " where s.curso_turma='$turma' ";
if ($selecao) $sql .= " and selecao = '$selecao' ";
$sql .= " order by s.nome";

// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar as tabelas de inscricao");
$i = 0;
while(!$resultado->EOF) {
	$id             = $resultado->fields['id'];
	$num_inscricao  = $resultado->fields['num_inscricao'];
	$cress          = $resultado->fields['cress'];
	$nome           = $resultado->fields['nome'];
	$email          = $resultado->fields['email'];
	$codigo_tel     = $resultado->fields['codigo_tel'];
	$telefone       = $resultado->fields['telefone'];
	$codigo_cel     = $resultado->fields['codigo_cel'];
	$celular        = $resultado->fields['celular'];
	$id_estagio     = $resultado->fields['id_estagio'];
	$id_instituicao = $resultado->fields['id_instituicao'];
	$instituicao    = $resultado->fields['instituicao'];
	$curso_selecao  = $resultado->fields['selecao'];

	// Verfico se eh supervisor cadastrado no estagio
	if ($cress <> 0) {
		$sql_cress = "select id from supervisores where cress='$cress'";
		// echo $nome . " " . $sql_cress . "<br>";
		$res_cress = $db->Execute($sql_cress);
		if($res_cress === false) die ("Não foi possível consultar a tabela supervisores");
		$supervisores_cress_id = $res_cress->fields['id'];
		// echo $cress . " " . $supervisores_cress_id .  " " . $nome . "<br>";
	}

	// Verifico se ja fez inscricao numa outra turma anterior
	if ($cress != 0) {
		$sql_inscricao_anterior  = "select id, nome, max(curso_turma) as inscricao_turma from curso_inscricao_supervisor ";
		$sql_inscricao_anterior .= "where curso_turma < '$turma' and cress = '$cress' group by curso_turma";
		// echo $sql_inscricao_anterior . "<br>";
		$res_inscricao_anterior = $db->Execute($sql_inscricao_anterior);
		if ($res_inscricao_anterior === false) die ("Nao foi possivel consultar a tabela curso_inscricao_supervisor");
		$id_inscricao_anterior = $res_inscricao_anterior->fields['id'];
		$nome_inscricao_anterior = $res_inscricao_anterior->fields['nome'];
		$inscricao_anterior = $res_inscricao_anterior->fields['inscricao_turma'];
		// echo $inscricao_anterior . "<br>";
	}

	if(empty($ordem)) {
		$ordem = "num_inscricao";
	} else {
		$indice = $ordem;
	}

	$matriz[$i][$ordem] = $$indice;

	$matriz[$i]['id'] = $id;
	$matriz[$i]['num_inscricao'] = $num_inscricao;
	$matriz[$i]['cress'] = $cress;
	$matriz[$i]['nome'] = $nome;
	$matriz[$i]['email'] = $email;
	$matriz[$i]['codigo_tel'] = $codigo_tel;
	$matriz[$i]['telefone'] = $telefone;
	$matriz[$i]['codigo_cel'] = $codigo_cel;
	$matriz[$i]['celular'] = $celular;
	$matriz[$i]['id_estagio'] = $id_estagio;
	$matriz[$i]['id_instituicao'] = $id_instituicao;
	$matriz[$i]['instituicao'] = $instituicao;
	$matriz[$i]['supervisores_id'] = $supervisores_cress_id;
	$matriz[$i]['selecao'] = $curso_selecao;
	$matriz[$i]['id_inscricao_anterior'] = $id_inscricao_anterior;
	$matriz[$i]['inscricao_anterior'] = $inscricao_anterior;

	// Reset destas variaveis
	unset($supervisores_cress_id);
	unset($cress);
	unset($id_estagio);
	unset($id_instituicao);
	unset($inscricao_anterior);

	$resultado->MoveNext();
	// echo $i . "<br>";
	$i++;
}

if(sizeof($matriz) <= 0) {
	echo "Arquivo vazio" . "<br>";
	if ($selecao == 1) {
		echo "<p>Ainda não há selecionados nesta turma</p>";
		echo "<meta http-equiv='refresh' content='1; URL=inscricao_lista.php?selecao=0&turma=$turma'>";
	}
	exit;
} else {
	reset($matriz);
	sort($matriz);
}

$sql_turma = "select curso_turma from curso_inscricao_supervisor group by curso_turma";
// echo $sql_turma . "<br>";
$res_turma = $db->Execute($sql_turma);
if($res_turma == false) die ("Não foi possível consultar a tabela de curso_inscricao_supervisor");
while (!$res_turma->EOF) {
	$turmas[] = $res_turma->fields['curso_turma'];
	$res_turma->MoveNext();
}

/*
 require("../../adodb/adodb-pager.inc.php");
 $pager = new ADODB_Pager($db,$sql);
 $pager->Render($rows_per_page='5');
 */

$smarty = new Smarty_estagio;

$smarty->assign("selecao",$selecao);
$smarty->assign("autentica",$sistema_autentica);
$smarty->assign("inscricaoRealizada",$inscricaoRealizada);
$smarty->assign("turma",$turma);
$smarty->assign("matriz",$matriz);
$smarty->assign("turmas",$turmas);
$smarty->display("inscricao_lista.tpl");

exit;

?>
