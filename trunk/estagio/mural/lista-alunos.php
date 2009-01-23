<?php

include_once("../autoriza.inc");

include_once("../db.inc");
include_once("../setup.php");

$ordem = $_REQUEST['ordem'];
if(empty($ordem)) $ordem = "nome";

$sql = "SELECT id_aluno, data FROM mural_inscricao WHERE periodo='". PERIODO_ATUAL . "' group by id_aluno";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela alunos");
$i = 0;	// Contador para a matriz inscricoes
while (!$resultado->EOF) {
		$id_aluno = $resultado->fields['id_aluno'];
		
		$sql_datas = "select min(data) as data_inicio, max(data) as data_ultima from mural_inscricao where id_aluno = $id_aluno";
		$resultado_datas = $db->Execute($sql_datas);
		$inscritos[$i]['data_inicio'] = $resultado_datas->fields['data_inicio'];
		$inscritos[$i]['data_ultima'] = $resultado_datas->fields['data_ultima'];
		// echo $sql_datas . "<br>";
		$sqlQuantidade = "select count(*) as quantidade from mural_inscricao where periodo = '" . PERIODO_ATUAL . "' and id_aluno=$id_aluno";
		// echo $sqlQuantidade . " "; // . "<br>";
		$resultadoQuantidade = $db->Execute($sqlQuantidade);
		if ($resultadoQuantidade === false) die ("Não foi possivel consultar a tabela mural_inscricao");
		$alunos[$i]['quantidade'] = $resultadoQuantidade->fields['quantidade'];
		$quantidadeInscricoes = $resultadoQuantidade->fields['quantidade'];
		// echo $quantidadeInscricoes . "<br>";
		
		$sqlAlunos = "select nome, registro, id, telefone, celular, email from alunos where registro=$id_aluno";
		// echo $sqlAlunos . "<br><br>";
		$resultadoAlunos = $db->Execute($sqlAlunos);
		if($resultadoAlunos === false) die ("Não foi possível consultar a tabela alunos");
		$quantidade = $resultadoAlunos->RecordCount();
		// echo $quantidade . "<br>";
		if ($quantidade == 0) {
				$sqlAlunosNovos = "select nome, registro, id, telefone, celular, email from alunosNovos where registro=$id_aluno";
				// echo "<span style='background-color: yellow'>Alunos novos</span>: " . $sqlAlunosNovos . "<br>";
				$resultadoAlunosNovos = $db->Execute($sqlAlunosNovos);
				if($resultadoAlunosNovos === false) die ("Não foi possível consultar a tabela alunosNovos");
				while (!$resultadoAlunosNovos->EOF) {
						$nome = $resultadoAlunosNovos->fields['nome'];
						$registro = $resultadoAlunosNovos->fields['registro'];
						// echo "Novos " . $registro . " " . $nome . "<br>";
						// $instituicao = $resultadoAlunosNovos->fields['instituicao'];
						$inscritos[$i]['nome'] = $resultadoAlunosNovos->fields['nome'];
						$inscritos[$i]['registro'] = $resultadoAlunosNovos->fields['registro'];
						$inscritos[$i]['id'] = $resultadoAlunosNovos->fields['id'];
						$inscritos[$i]['telefone'] = $resultadoAlunosNovos->fields['telefone'];
						$inscritos[$i]['celular'] = $resultadoAlunosNovos->fields['celular'];
						$inscritos[$i]['email'] = $resultadoAlunosNovos->fields['email'];
						$inscritos[$i]['instituicao'] = $resultadoAlunosNovos->fields['instituicao'];
						$inscritos[$i]['quantidade'] = $quantidadeInscricoes;
						
						$inscritos[$i]['data'] = date("d-m-Y",strtotime($data));
						
						$inscritos[$i]['aluno'] = 0; // Aluno novo

						// Entregou o termo de compromiso?
						$sql_estagiario = "select id from estagiarios where registro = '$id_aluno' and periodo ='" . PERIODO_ATUAL . "'";
						$resultado_estagiario = $db->Execute($sql_estagiario);
						$q_alunos = $resultado_estagiario->RecordCount();
						if ($q_alunos > 0) {
							 $inscritos[$i]['inscrito'] = 1; // Ja fez inscricao
						} else {
							 $inscritos[$i]['inscrito'] = 0; // Ainda nao
						}
						
						$i++;
						$resultadoAlunosNovos->MoveNext();
				}
		} else {
				while(!$resultadoAlunos->EOF) {
						$nome = $resultadoAlunos->fields['nome'];
						$registro = $resultadoAlunos->fields['registro'];
						// echo "<span style='background-color:green'>Alunos velhos:</span> " . $registro . " " . $nome . "<br>";
						// $instituicao = $resultadoAlunos->fields['instituicao'];
						$inscritos[$i]['nome'] = $resultadoAlunos->fields['nome'];
						$inscritos[$i]['registro'] = $resultadoAlunos->fields['registro'];
						$inscritos[$i]['id'] = $resultadoAlunos->fields['id'];
						$inscritos[$i]['telefone'] = $resultadoAlunos->fields['telefone'];
						$inscritos[$i]['celular'] = $resultadoAlunos->fields['celular'];
						$inscritos[$i]['email'] = $resultadoAlunos->fields['email'];
						$inscritos[$i]['instituicao'] = $resultadoAlunos->fields['instituicao'];
						$inscritos[$i]['quantidade'] = $quantidadeInscricoes;
						
						$inscritos[$i]['data'] = date("d-m-Y",strtotime($data));
						
						$inscritos[$i]['aluno'] = 1; // Aluno estagiario

						// Entregou o termo de compromiso?
						$sql_estagiario = "select id from estagiarios where registro = '$id_aluno' and periodo ='" . PERIODO_ATUAL . "'";
						$resultado_estagiario = $db->Execute($sql_estagiario);
						$q_alunos = $resultado_estagiario->RecordCount();
						if ($q_alunos > 0) {
							 $inscritos[$i]['inscrito'] = 1; // Ja fez inscricao
						} else {
							 $inscritos[$i]['inscrito'] = 0; // Ainda nao
						}
						
						// echo "<br>";
						
						$i++;				
						$resultadoAlunos->MoveNext();
					}
		}

		$resultado->MoveNext();
}

for($i=0;$i<sizeof($inscritos);$i++) {
	$n_inscritos[$i][$ordem] = $inscritos[$i][$ordem];
	$n_inscritos[$i]['nome'] = $inscritos[$i]['nome'];
	$n_inscritos[$i]['registro'] = $inscritos[$i]['registro'];
	$n_inscritos[$i]['id'] = $inscritos[$i]['id'];
	$n_inscritos[$i]['telefone'] = $inscritos[$i]['telefone'];
	$n_inscritos[$i]['celular'] = $inscritos[$i]['celular'];
	$n_inscritos[$i]['email'] = $inscritos[$i]['email'];
	$n_inscritos[$i]['instituicao'] = $inscritos[$i]['instituicao'];
	$n_inscritos[$i]['quantidade'] = $inscritos[$i]['quantidade'];
	$n_inscritos[$i]['aluno'] = $inscritos[$i]['aluno'];
	$n_inscritos[$i]['inscrito'] = $inscritos[$i]['inscrito'];
	$n_inscritos[$i]['data_inicio'] = $inscritos[$i]['data_inicio'];
	$n_inscritos[$i]['data_ultima'] = $inscritos[$i]['data_ultima'];
}

if (sizeof($n_inscritos) != 0) {
	sort($n_inscritos);
}

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("mural_autentica",$mural_autentica);
$smarty->assign("totalAlunos",$i);
$smarty->assign("alunos",$n_inscritos);
$smarty->display("lista-alunos.tpl");

?>
