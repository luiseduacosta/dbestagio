<?php

include_once("../../setup.php");

$ordem = $_GET['ordem'];

$smarty = new Smarty_estagio;
/*
$sql  = "select e.id as estagio_id, e.instituicao, s.id as supervisor_id, s.cress, s.nome, ";
$sql .= "s.email ";
$sql .= "from supervisores as s, inst_super as i, estagio as e ";
$sql .= "where s.id=i.id_supervisor and i.id_instituicao=e.id";
*/

$sql  = "select e.id as estagio_id, e.instituicao ";
$sql .= ", s.id as supervisor_id, s.cress, s.nome, s.email ";
// $sql .= ", c.id as id_curso ";
// $sql .= ", max(t.periodo) as turma ";
$sql .= " from supervisores as s ";
$sql .= " join inst_super as i on s.id = i.id_supervisor ";
$sql .= " join estagio as e on e.id = i.id_instituicao ";
// $sql .= " left join estagiarios as t on s.id = t.id_supervisor ";
// $sql .= " group by t.id_supervisor";
// $sql .= " left outer join curso_inscricao_supervisor as c on s.cress = c.cress ";
// $sql .= " group by c.cress";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado == false) die ("Não foi possível consultar as tabelas");
while (!$resultado->EOF) {
	if(empty($ordem))
		$ordem = "nome";
	else
		$indice = $ordem;

	$estagio_id_instituicao = $resultado->fields['estagio_id'];
	$id_supervisor          = $resultado->fields['supervisor_id'];
	$cress                  = $resultado->fields['cress'];
	$turma                  = $resultado->fields['turma'];
	$nome_supervisor        = $resultado->fields['nome'];
	$email_supervisor       = $resultado->fields['email'];
	$estagio_instituicao    = $resultado->fields['instituicao'];

	$matriz[$i][$ordem]           = $$indice;
	$matriz[$i]['id_instituicao'] = $estagio_id_instituicao;
	$matriz[$i]['id_supervisor']  = $id_supervisor;
	$matriz[$i]['nome']           = $nome_supervisor;
	$matriz[$i]['instituicao']    = $estagio_instituicao;
	$matriz[$i]['email']    	  = $email_supervisor;
	$matriz[$i]['id_curso']       = $resultado->fields['id_curso'];

	// Pego a informacao sobre turma de alunos
	$sqlturma = "select id, max(periodo) as turma from estagiarios where id_supervisor = $id_supervisor group by id_supervisor";
	// echo $sqlturma . "<br>";
	$res_turma = $db->Execute($sqlturma);
	if ($res_turma === false) die ("Não foi possivel consultar a tabela estagiarios");
	$turma = $res_turma->fields['turma'];
	$matriz[$i]['turma'] = $turma;

	// Pego a informacao sobre curso de supervisores
	if(!empty($cress)) {

			for ($k=0;$k < strlen($cress);$k++) {
				$j = ord($cress[$k]);
				// echo $cress . " " . $k . " -> " . $j . " <br /> ";
				if ($j < 48 || $j > 57) {
					$okcress = "0";
					$k = strlen($cress);
					// echo $cress . " " . $k . " " . $j . " letra <br /> ";
				} else {
//					// echo $i . " " . $j . " numero <br /> ";
					$okcress = $cress;
				}
			}
			if($okcress <> 0) {
				$sqlcurso = "select id from curso_inscricao_supervisor where cress=$okcress";
				// echo $sqlcurso . "<br />";
				$supervisores_curso = $db->Execute($sqlcurso);
				if($supervisores_curso === false) die ("Não foi possível consultar a tabela curso_inscricao_supervisores");
				$matriz[$i]['id_curso'] = $supervisores_curso->fields['id'];
				$id_curso = $supervisores_curso->fields['id'];
				// echo $id_curso . "<br>";
			}
	}

	$matriz[$i]['cress'] = $cress;
	$resultado->MoveNext();
	$i++;

}

reset($matriz);
sort($matriz);

/* Debugg
for($i=0;$i<sizeof($matriz);$i++) {
	print $matriz[$i]['id'] . " ";
	print $matriz[$i]['nome'] . " ";
	print $matriz[$i]['instituicao'] . "<br>";
}
*/

$smarty->assign("pagina_atual",$PHP_SELF);
$smarty->assign("supervisores",$matriz);
$smarty->display("supervisores.tpl");

$db->Close();

exit;

?>