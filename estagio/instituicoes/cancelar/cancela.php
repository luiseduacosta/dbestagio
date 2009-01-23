<?php

include_once("../../autentica.inc");
include_once("../../db.inc");

$id_instituicao = $_REQUEST['id_instituicao'];
$indice = $_REQUEST['indice'];

// Obtengo o(s) numero(s) dos supervisores para poder deletar 
$sql_super  = "select s.id as num_supervisor ";
$sql_super .= "from supervisores as s, "; 
$sql_super .= "inst_super as i ";
$sql_super .= "where s.id=i.id_supervisor ";
$sql_super .= "and i.id_instituicao=$id_instituicao";
$res_sql_super = $db->Execute($sql_super);
if($res_sql_super === false) die ("N�o foi poss�vel consultar as tabelas supervisores/inst_super");
$quantidade = $res_sql_super->RecordCount();

// Obtengo a quantidade de alunos que estagiaram nessa instituicao
$sqlAlunos = "select id_aluno from estagiarios where id_instituicao=$id_instituicao";
$resultadoAluno = $db->Execute($sqlAlunos);
if($resultadoAluno === false) die ("N�o foi poss�vel consultar a tabela estagiarios");
$quantidadeAluno = $resultadoAluno->RecordCount();

if ($quantidade > 0) {
	echo "Opera��o abortada. � necess�rio primeiramente excluir os supervisores que trabalham nessa institui��o";
	echo "<meta http-equiv='refresh' content='2;../exibir/ver_cada.php?indice=$indice' />";
	exit;
} elseif ($quantidadeAluno > 0) {
	echo "Opera��o abortada. � necess�rio primeiramente excluir os alunos que realizaram est�gio nesta institui��o";
	echo "<meta http-equiv='refresh' content='2;../exibir/ver_cada.php?indice=$indice' />";
	exit;
} else {
	// Elimino o registro da instituicao
	$sql_estagio = "delete from estagio where id='$id_instituicao'";
	$resultado_estagio = $db->Execute($sql_estagio);
	if($resultado_estagio == false) die ("N�o foi poss�vel excluir o registro da tabela estagio");
	echo "<p>Institui��o exclu�da</p>";
	echo "<meta http-equiv='refresh' content='2;../exibir/ver_cada.php?indice=$indice' />";
}

exit;

?>