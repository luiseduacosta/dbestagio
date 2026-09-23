<?php

include_once("../autentica.inc");

$registro = isset($_GET['registro']) ? $_GET['registro'] : NULL ;

echo "
<p><a href=\"javascript:history.back(1)\">Voltar</a></p>
";

// Aluno cadastrado
$sql = "select id, registro, nome from alunos where registro='$registro'";
$resultado_aluno = $db->Execute($sql);
if ($resultado_aluno === false) die ("Nao foi possivel consultar as tabela alunos");
while (!$resultado_aluno->EOF) {
	$id_aluno   = $resultado_aluno->fields['id'];
	$registro = $resultado_aluno->fields['registro'];
	$nome_aluno = $resultado_aluno->fields['nome'];
	$resultado_aluno->MoveNext();
}

$sql_estagiario  = "SELECT estagiarios.id, estagiarios.periodo, estagiarios.nivel, " .
		"instituicoes.instituicao, supervisores.nome " .
		"FROM estagiarios " .
		"left outer join instituicoes on instituicoes.id = estagiarios.instituicao_id " .
		"left outer join supervisores on supervisores.id = estagiarios.supervisor_id " .
		"where estagiarios.registro = '$registro' " .
		"order by estagiarios.periodo";

		// echo $sql_estagiario . "<br>";
		
		$resultado_estagiario = $db->Execute($sql_estagiario);
		if($resultado_estagiario === false) die ("1 Não foi possível consultar as tabelas estagiarios, instituicoes, supervisores");
		$quantidade_estagiario = $resultado_estagiario->RecordCount();
		if ($quantidade_estagiario > 0) {
			$i = 0;
			while (!$resultado_estagiario->EOF) {
				$estagiarios[$i]['id']          = $resultado_estagiario->fields['id'];
				$estagiarios[$i]['periodo']     = $resultado_estagiario->fields['periodo'];
				$estagiarios[$i]['nivel']       = $resultado_estagiario->fields['nivel'];
				$estagiarios[$i]['turno']       = NULL;
				$estagiarios[$i]['instituicao'] = $resultado_estagiario->fields['instituicao'];
				$estagiarios[$i]['supervisor']  = $resultado_estagiario->fields['nome'];
				$supervisor = $resultado_estagiario->fields['nome'];
				$resultado_estagiario->MoveNext();
				$i++;
			}
		}

// Dados das inscricoes para seleçao de estagio
$sql  = "select mural_estagios.id as muralestagio_id, mural_estagios.instituicao, inscricoes.data ";
$sql .= " from inscricoes ";
$sql .= " inner join mural_estagios on mural_estagios.id = inscricoes.muralestagio_id";
$sql .= " where inscricoes.periodo = '" . PERIODO_ATUAL . "' and inscricoes.registro='$registro'";
$sql .= " order by instituicao";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela inscricoes");
$i = 0;
while (!$resultado->EOF) {
	$instituicoes[$i]['muralestagio_id'] = $resultado->fields['muralestagio_id'];
	$instituicoes[$i]['instituicao'] = $resultado->fields['instituicao'];
	$instituicoes[$i]['data'] = $resultado->fields['data'];
	$resultado->MoveNext();
	$i++;
}

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica", $sistema_autentica);
// Tabela de estagios anteriores
$smarty->assign("aluno", $aluno);
$smarty->assign("estagiarios", $estagiarios);
$smarty->assign("aluno_id", $aluno_id);
$smarty->assign("registro", $registro);
$smarty->assign("nome_aluno", $nome_aluno);
$smarty->assign("instituicoes", $instituicoes);

$smarty->display("../../mural/ver-aluno.tpl");

?>
