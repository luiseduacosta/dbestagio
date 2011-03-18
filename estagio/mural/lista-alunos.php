<?php

include_once("../autoriza.inc");
include_once("../setup.php");

$ordem = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : nome;

$sql = "SELECT id_aluno, data " .
		" FROM mural_inscricao " .
		" WHERE periodo='". PERIODO_ATUAL . "' group by id_aluno";
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

		// Capturo a data de ingresso na universidade
		$sql_ingresso = "select periodo, turno from alunos_ingresso where registro='$id_aluno'";
		// echo $sql_ingresso . "<br>";
		$res_ingresso = $db->Execute($sql_ingresso);
		$inscritos[$i]['data_ingresso'] = $periodo_ingresso = $res_ingresso->fields['periodo'];
		$inscritos[$i]['turno'] = $periodo_ingresso = $res_ingresso->fields['turno'];
		// echo $periodo_ingresso = $res_ingresso->fields['periodo'] . "<br>";

		// Calculo a quantidade de inscricoes por aluno
		$sqlQuantidade = "select count(*) as quantidade from mural_inscricao where periodo = '" . PERIODO_ATUAL . "' and id_aluno=$id_aluno";
		// echo $sqlQuantidade . " "; // . "<br>";
		$resultadoQuantidade = $db->Execute($sqlQuantidade);
		if ($resultadoQuantidade === false) die ("Não foi possivel consultar a tabela mural_inscricao");
		$quantidadeInscricoes = $resultadoQuantidade->fields['quantidade'];
		// echo $quantidadeInscricoes . "<br>";
	
		// Verifico se eh um aluno estagiario
		$sqlAlunos = "select nome, registro, id, telefone, celular, email from alunos where registro=$id_aluno";
		// echo $sqlAlunos . "<br><br>";
		$resultadoAlunos = $db->Execute($sqlAlunos);
		if($resultadoAlunos === false) die ("Não foi possível consultar a tabela alunos");
		$quantidade = $resultadoAlunos->RecordCount();
		// echo $id_aluno . " " . $quantidade . "<br>";

		// Se nao esta como aluno estagiario entao busco em alunosNovos
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
						// $inscritos[$i]['instituicao'] = $resultadoAlunosNovos->fields['instituicao'];
						// $inscritos[$i]['nivel'] = $registro_nivel;
						$inscritos[$i]['quantidade'] = $quantidadeInscricoes;
						
						$inscritos[$i]['data'] = date("d-m-Y",strtotime($data));
						
						$inscritos[$i]['aluno'] = 0; // Aluno novo

						// Solicitou o termo de compromiso?
						$sql_estagiario = "select id, tc from estagiarios where registro = '$id_aluno' and periodo ='" . PERIODO_ATUAL . "'";
						// echo $sql_estagiario . "<br>";
						$resultado_estagiario = $db->Execute($sql_estagiario);
						$inscritos[$i]['inscrito'] = $resultado_estagiario->fields['tc'];

						// Para ordenar a tabela pela variavel ordem
						$criterio[] = $inscritos[$i][$ordem];

						$i++;
						$resultadoAlunosNovos->MoveNext();
				}
		// Aluno estagiario
		} else {
				while(!$resultadoAlunos->EOF) {
						$nome = $resultadoAlunos->fields['nome'];
						$registro = $resultadoAlunos->fields['registro'];

						// Busco alunos estagiarios no periodo atual
						$sql_nivel = "select registro, max(nivel) as nivel from estagiarios where registro = '$registro' and periodo ='" . PERIODO_ATUAL ."' group by id_aluno";
						// echo $sql_nivel . "<br>";
						$res_nivel = $db->Execute($sql_nivel);
						$registro_nivel = $res_nivel->fields['nivel'];
						// echo 'Nivel ' . $registro_nivel . "<br>";

						// A pesar de ser estagiario eh aluno novo porque esta em estagio I no periodo atual						
						if ($registro_nivel == '1') {
							$inscritos[$i]['aluno'] = 0; // Aluno novo em estagio I
						} else {
							$inscritos[$i]['aluno'] = 1; // Aluno estagiario
						}
						
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
						$inscritos[$i]['nivel'] = $registro_nivel;
						$inscritos[$i]['data'] = date("d-m-Y",strtotime($data));
						
						// Entregou o termo de compromiso?
						$sql_estagiario = "select id, tc from estagiarios where registro = '$id_aluno' and periodo ='" . PERIODO_ATUAL . "'";
						// echo $sql_estagiario . "<br>";
						$resultado_estagiario = $db->Execute($sql_estagiario);
						$inscritos[$i]['inscrito'] = $resultado_estagiario->fields['tc'];

						// Para ordenar a tabela pela variavel ordem
						$criterio[] = $inscritos[$i][$ordem];

						$i++;				
						$resultadoAlunos->MoveNext();
					}
		}

		$resultado->MoveNext();
}

// Ordeno a tabela pela variavel ordem
if (isset($criterio)) array_multisort($criterio, SORT_ASC, $inscritos);

$smarty = new Smarty_estagio;

$smarty->assign("periodo_atual",PERIODO_ATUAL);
$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("totalAlunos",$i);
$smarty->assign("alunos",$inscritos);
$smarty->display("../../mural/lista-alunos_mural.tpl");

?>
