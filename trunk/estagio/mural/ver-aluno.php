<?php

include_once("../autoriza.inc");

include_once("../db.inc");
include_once("../setup.php");

$id_aluno = isset($_GET['id_aluno']) ? $_GET['id_aluno'] : NULL ;
$aluno = $_GET['aluno']; // Para saber se o aluno eh novo ou já conhecido
$registro = $id_aluno;

echo "
<p><a href=\"javascript:history.back(1)\">Voltar</a></p>
";

// Aluno já cadastrado
$sql = "select id, registro, nome from alunos where registro='$id_aluno'";
// echo $sql . "<br>";
$resultado_aluno = $db->Execute($sql);
if($resultado_aluno === false) die ("Não foi possível consultar as tabelas estagiarios, estagio, supervisores");
$quantidade = $resultado_aluno->RecordCount();
// Aluno novo
if ($quantidade == 0) {
		$sql_aluno = "select id, registro, nome from alunosNovos where registro=$id_aluno";
		$aluno = 0;
		// Aluno já conhecido
} else {
		$sql_aluno = "select id, registro, nome from alunos where registro='$id_aluno'";	
		$aluno = 1;
		$sql_estagiario  = "SELECT estagiarios.id, estagiarios.periodo, estagiarios.nivel, estagiarios.turno, " .
		"estagio.instituicao, supervisores.nome " .
		"FROM estagiarios " .
		"left outer join estagio on estagio.id = estagiarios.id_instituicao " .
		"left outer join supervisores on estagiarios.id_supervisor = supervisores.id " .
		"where estagiarios.registro = '$id_aluno' " .
		"order by estagiarios.periodo";

		// echo $sql_estagiario . "<br>";
		
		$resultado_estagiario = $db->Execute($sql_estagiario);
		if($resultado_estagiario === false) die ("1 Não foi possível consultar as tabelas estagiarios, estagio, supervisores");
		$quantidade_estagiario = $resultado_estagiario->RecordCount();
		if ($quantidade_estagiario > 0) {
			$i = 0;
			while (!$resultado_estagiario->EOF) {
				$estagiarios[$i]['id']          = $resultado_estagiario->fields['id'];
				$estagiarios[$i]['periodo']     = $resultado_estagiario->fields['periodo'];
				$estagiarios[$i]['nivel']       = $resultado_estagiario->fields['nivel'];
				$estagiarios[$i]['turno']       = $resultado_estagiario->fields['turno'];
				$estagiarios[$i]['instituicao'] = $resultado_estagiario->fields['instituicao'];
				$estagiarios[$i]['supervisor']  = $resultado_estagiario->fields['nome'];
				$supervisor = $resultado_estagiario->fields['nome'];
				$resultado_estagiario->MoveNext();
				$i++;
			}
		}
}

$resultado_alunos = $db->Execute($sql_aluno);
if($resultado_alunos === false) die ("Não foi possível consultar a tabela alunos ou alunosNovos");
while (!$resultado_alunos->EOF) {
	$id_aluno   = $resultado_alunos->fields['id'];
	$registro = $resultado_alunos->fields['registro'];
	$nome_aluno = $resultado_alunos->fields['nome'];
	$resultado_alunos->MoveNext();
}

// Dados das inscrições para seleção de estágio
$sql  = "select id_instituicao, instituicao, data ";
$sql .= " from mural_inscricao ";
$sql .= " inner join mural_estagio on mural_inscricao.id_instituicao = mural_estagio.id";
$sql .= " where mural_inscricao.periodo = '" . PERIODO_ATUAL . "' and mural_inscricao.id_aluno='$registro'";
$sql .= " order by instituicao";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela mural_inscricao");
$i = 0;
while (!$resultado->EOF) {
	$instituicoes[$i]['id_instituicao'] = $resultado->fields['id_instituicao'];
	$instituicoes[$i]['instituicao'] = $resultado->fields['instituicao'];
	$instituicoes[$i]['data'] = $resultado->fields['data'];
	$resultado->MoveNext();
	$i++;
}

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("mural_autentica",$mural_autentica);
// Tabela de estagios anteriores
$smarty->assign("aluno",$aluno);
$smarty->assign("estagiarios",$estagiarios);
$smarty->assign("id_aluno",$id_aluno);
$smarty->assign("registro",$registro);
$smarty->assign("nome_aluno",$nome_aluno);
$smarty->assign("instituicoes",$instituicoes);

$smarty->display("ver-aluno.tpl");

?>
