<?php

include_once("../autoriza.inc");
include_once("../setup.php");

$ordem = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : NULL;

$sql = "SELECT id_aluno, data FROM mural_inscricao WHERE periodo='". PERIODO_ATUAL . "' group by id_aluno";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela alunos");

$i = 0;
while (!$resultado->EOF) {
		$id_aluno = $resultado->fields['id_aluno'];

		$sqlQuantidade = "select count(*) as quantidade from mural_inscricao where periodo = '" . PERIODO_ATUAL . "' and id_aluno=$id_aluno";
		// echo $sqlQuantidade . "<br>";
		$resultadoQuantidade = $db->Execute($sqlQuantidade);
		if ($resultadoQuantidade === false) die ("Não foi possivel consultar a tabela mural_inscricao");
		$quantidadeInscricoes = $resultadoQuantidade->fields['quantidade'];
		//  echo $quantidadeInscricoes . "<br>";

		// Pego todos os alunos estagiarios menos os que ja entregaram o TC		
		$sqlAlunos = "select alunos.id from alunos join estagiarios on alunos.id = estagiarios.id_aluno " .
				" where alunos.registro='$id_aluno' and estagiarios.periodo != '" . PERIODO_ATUAL . "' " .
				" group by estagiarios.id_aluno ";
	
		// echo $sqlAlunos . "<br>";

		$resultadoAlunos = $db->Execute($sqlAlunos);
		if($resultadoAlunos === false) die ("Não foi possível consultar a tabela alunos");
		$quantidade = $resultadoAlunos->RecordCount();

		if ($quantidade == 0) {
				$sqlAlunosNovos = "select nome, registro, id, telefone, celular, email, cpf, identidade, nascimento from alunosNovos where registro='$id_aluno'";
				// echo $i . " " . $sqlAlunosNovos . "<br>";
				
				$resultadoAlunosNovos = $db->Execute($sqlAlunosNovos);
				if($resultadoAlunosNovos === false) die ("Não foi possível consultar a tabela alunosNovos");
				while(!$resultadoAlunosNovos->EOF) {
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
						$inscritos[$i]['cpf'] = $resultadoAlunosNovos->fields['cpf'];			
						$inscritos[$i]['identidade'] = $resultadoAlunosNovos->fields['identidade'];						
						$inscritos[$i]['nascimento'] = $resultadoAlunosNovos->fields['nascimento'];
						$inscritos[$i]['instituicao'] = $resultadoAlunosNovos->fields['instituicao'];
						$inscritos[$i]['quantidade'] = $quantidadeInscricoes;

						// Busco se o aluno novo ja esta com o termo de compromisso
						$sql_estagiarios_novos = "select tc, nivel from estagiarios where registro='$id_aluno'";
						// echo $sql_estagiarios_novos . "<br>";
						$res_estagiarios_novos = $db->Execute($sql_estagiarios_novos);
						$inscritos[$i]['inscrito'] = $res_estagiarios_novos->fields['tc'];
						$inscritos[$i]['nivel'] = $res_estagiarios_novos->fields['nivel'];

						// Data da ultima intervencao do aluno no Banco de Dados
						$sql_estagiarios_data = "select max(data) as data_ultima from mural_inscricao where id_aluno='$id_aluno'";
						// echo $sql_estagiarios_data . "<br>";
						$res_estagiarios_data = $db->Execute($sql_estagiarios_data);
						$inscritos[$i]['data_ultima'] = date("d-m-Y",strtotime($res_estagiarios_data->fields['data_ultima']));
						// $nivel = $res_estagiarios_novos->fields['nivel'];
						// echo " nivel " . $nivel . "<br>"; 
						$inscritos[$i]['aluno'] = 0;

						// Capturo a data de ingresso na universidade
						$sql_ingresso = "select periodo, turno from alunos_ingresso where registro='$id_aluno'";
						// echo $sql_ingresso . "<br>";
						$res_ingresso = $db->Execute($sql_ingresso);
						$inscritos[$i]['data_ingresso'] = $periodo_ingresso = $res_ingresso->fields['periodo'];
						$inscritos[$i]['turno'] = $periodo_ingresso = $res_ingresso->fields['turno'];
						// echo $periodo_ingresso = $res_ingresso->fields['periodo'] . "<br>";

						// Para ordernar a tabela
						$criterio[] = $inscritos[$i][$ordem];

						$i++;

						$resultadoAlunosNovos->MoveNext();
				}
		} 
		$resultado->MoveNext();
}

// Ordeno a tabela pela variavel ordem
if (isset($criterio)) array_multisort($criterio, SORT_ASC, $inscritos);

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("totalAlunos",$i);
$smarty->assign("alunos",$inscritos);
$smarty->display("lista-alunos_mural.tpl");

?>
