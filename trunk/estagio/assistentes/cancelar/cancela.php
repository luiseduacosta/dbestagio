<?php

include_once("../../autentica.inc");

$id_supervisor = isset($_REQUEST['id_supervisor']) ? $_REQUEST['id_supervisor'] : NULL;
$indice = isset($_REQUEST['indice']) ? $_REQUEST['indice'] : NULL;

include_once("../../db.inc");

// Nao excluir se supervisionou alunos
$sql = "select id from estagiarios where id_supervisor=$id_supervisor";
$resultado = $db->Execute($sql);
$quantidade = $resultado->RecordCount();
if($quantidade != 0) {
	echo "<meta http-equiv='refresh' content='2;url=../exibir/ver_cada.php?id_supervisor=$id_supervisor' />";
	die ("Não é possível excluir o supervisor porque orientou $quantidade alunos");
}

// Nao excluir se realizou o curso para supervisores
$sql_cress = "select cress from supervisores where id=$id_supervisor";
$resultado_cress = $db->Execute($sql_cress);
$cress = $resultado_cress->fields['cress'];
// if(!empty($cress)) {
if (ctype_digit($cress)) {
	$sqlcurso = "select id from curso_inscricao_supervisor where cress=$cress";
	// echo $sqlcurso . "<br />";
	$supervisores_curso = $db->Execute($sqlcurso);
	if($supervisores_curso === false) die ("Não foi possivel consultar a tabela curso_inscricao_supervisores");
	$id_curso = $supervisores_curso->fields['id'];
	if(isset($id_curso)) {
		// echo "Id curso: " . $id_curso . "<br>";
		// echo "<meta http-equiv='refresh' content='2;url=../exibir/ver_cada.php?id_supervisor=$id_supervisor' />";
		// die ("Não é possível excluir o supervisor porque realizou inscrição para o curso para supervisores");
	}
	if ($cress != 0) {
		echo "<meta http-equiv='refresh' content='0;url=../exibir/ver_cada.php?id_supervisor=$id_supervisor' />";
		die ("Não é possível excluir o supervisor porque tem número de CRESS cadastrado");
	}
}

$sql = "delete from supervisores where id=$id_supervisor";
// echo $sql. "<br>";
// die;
$resultado = $db->Execute($sql);
if($resultado == false) die ("Não foi possível cancelar o registro da tabela supervisores");

// Obtengo as instituicoes na que trabalha o supervisor
$sql_estagio = "select * from inst_super where id_supervisor=$id_supervisor";
$res_estagio = $db->Execute($sql_estagio);
if($res_estagio === false) die ("Não foi possível consultar a tabela inst_super");
$q_inst_super = $res_estagio->RecordCount();
// echo $q_inst_super . "<br>";
// Se nao este em nenhuma instituicao sair
if ($q_inst_super == 0) {
	echo "<meta HTTP-EQUIV='refresh' CONTENT='0,URL=../exibir/ver_cada.php?indice=0'>";
	exit;
}

$i = 0;
while(!$res_estagio->EOF) {
    $id_instituicao[$i] = $res_estagio->fields['id_instituicao'];
    $res_estagio->MoveNext();
    $i++;
}

// Busco em inst_super si outros supervisores trabalham em essa instituicao
$q_instituicoes = sizeof($id_instituicao);
// echo "Quantidade de instituicoes " . $q_instituicoes . "<br>";
for ($i=0; $i<$q_instituicoes; $i++) {
    $num_instituicao = $id_instituicao[$i];
    $sql_inst_super_outros = "select * from inst_super where id_instituicao=$num_instituicao";
}

// Excluo tambem a relacao entre o supervisor e a instituicao
$sql_inst_super = "delete from inst_super where id_supervisor=$id_supervisor";
$res_inst_super = $db->Execute($sql_inst_super);
if($res_inst_super === false) die ("Não foi possível cancelar o registro da tabela inst_super");

// echo "<p>Registro cancelado</p>";
// echo "Indice " . $indice . "<br>";

header("Location:../../assistentes/exibir/ver_cada.php?indice=$indice");

exit;

?>
