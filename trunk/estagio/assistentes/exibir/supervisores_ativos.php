<?php

include_once("../../setup.php");

// Verifico se o usuario esta logado
if (isset($_REQUEST['usuario_senha'])) {
    $usuario = $_REQUEST['usuario_senha'];
    if ($usuario) 
	$logado = 1;
}

$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : 'nome';

$sql_turma = "select max(periodo) as turma from estagiarios";
$resultado_turma = $db->Execute($sql_turma);
if ($resultado_turma === false) die ("Não foi possível consultar a tabela estagiarios");
$turma = $resultado_turma->fields['turma'];

$sql  = "select supervisores.id as supervisor_id, supervisores.cress, supervisores.nome, supervisores.email, supervisores.celular, supervisores.telefone, estagio.id as estagio_id, estagio.instituicao ";
$sql .= " from estagiarios ";
$sql .= " inner join supervisores on estagiarios.id_supervisor = supervisores.id ";
$sql .= " inner join estagio on estagiarios.id_instituicao = estagio.id ";
$sql .= " where estagiarios.periodo='$turma'";
$sql .= " group by estagiarios.id_supervisor";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado == false) die ("Não foi possível consultar as tabelas");
while (!$resultado->EOF) {

	$matriz[$i]['id_instituicao'] = $resultado->fields['estagio_id'];
	$matriz[$i]['id_supervisor']  = $resultado->fields['supervisor_id'];
	$matriz[$i]['nome']           = $resultado->fields['nome'];
	$matriz[$i]['email']          = $resultado->fields['email'];
	$matriz[$i]['telefone']       = $resultado->fields['telefone'];
	$matriz[$i]['celular']        = $resultado->fields['celular'];
	$matriz[$i]['instituicao']    = $resultado->fields['instituicao'];
	$matriz[$i]['cress']          = $resultado->fields['cress'];
	
	// Pego a informacao sobre turma de alunos
	$id_supervisor = $resultado->fields['supervisor_id'];
	$sqlturma = "select id, max(periodo) as turma from estagiarios where id_supervisor = $id_supervisor group by id_supervisor";
	// echo $sqlturma . "<br>";
	$res_turma = $db->Execute($sqlturma);
	if ($res_turma === false) die ("Não foi possivel consultar a tabela estagiarios");
	$turma = $res_turma->fields['turma'];
	$matriz[$i]['turma'] = $turma;

	// Calculo a quantidade de periodos que o supervisor trabalha com alunos
	$sql_periodos = "select count(distinct periodo) as q_periodos from estagiarios where id_supervisor=$id_supervisor";
	// echo $sql_periodos . "<br>";	
	$res_periodos = $db->Execute($sql_periodos);
	if ($res_periodos === false) die ("Não foi possivel consultar a tabela estagiarios");
	$q_periodos = $res_periodos->fields['q_periodos'];	
	$matriz[$i]['q_periodos'] = $q_periodos;

	// Pego a informacao sobre curso de supervisores
	$cress = $resultado->fields['cress'];

	// Verifico que seja numeros
	if (ctype_digit($cress)) {
            // O numero tem que ser diferente de 0
            if ($cress <> 0) {
			$sqlcurso = "select id from curso_inscricao_supervisor where cress=$cress";
			// echo $sqlcurso . "<br />";
			$supervisores_curso = $db->Execute($sqlcurso);
			if ($supervisores_curso === false) die ("Não foi possivel consultar a tabela curso_inscricao_supervisores");
			$matriz[$i]['id_curso'] = $supervisores_curso->fields['id'];
                        // echo $cress . " " . $supervisores_curso->fields['id'] . "<br>";
			$id_curso = $supervisores_curso->fields['id'];
			// echo $id_curso . "<br>";
            }
	}

	$resultado->MoveNext();
	$i++;
}

if (sizeof($matriz) > 0) {
	reset($matriz);

	for($i=0;$i<sizeof($matriz);$i++) {
		$chave[$i] = $matriz[$i][$ordem];
	}

	array_multisort($chave,$matriz);
	// var_dump($matriz);

}

/* Debugg
for($i=0;$i<sizeof($matriz);$i++)
{
	print $matriz[$i]['id'] . " ";
	print $matriz[$i]['nome'] . " ";
	print $matriz[$i]['instituicao'] . "<br>";
}
*/

$smarty = new Smarty_estagio;
$smarty->assign("logado",$logado);
$smarty->assign("supervisores",$matriz);
$smarty->display("supervisores.tpl");

$db->Close();

exit;

?>
