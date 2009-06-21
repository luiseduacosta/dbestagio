<?php

include_once("../autoriza.inc");
include_once("../setup.php");

$ordem = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : NULL;

$sql = "SELECT id_aluno, data FROM mural_inscricao WHERE periodo='". PERIODO_ATUAL . "' group by id_aluno";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);
if($resultado === false) die ("N�o foi poss�vel consultar a tabela alunos");
while (!$resultado->EOF) {
		$id_aluno = $resultado->fields['id_aluno'];

		$sqlQuantidade = "select count(*) as quantidade from mural_inscricao where periodo = '" . PERIODO_ATUAL . "' and id_aluno=$id_aluno";
		// echo $sqlQuantidade . "<br>";
		$resultadoQuantidade = $db->Execute($sqlQuantidade);
		if ($resultadoQuantidade === false) die ("N�o foi possivel consultar a tabela mural_inscricao");
		$alunos[$i]['quantidade'] = $resultadoQuantidade->fields['quantidade'];
		$quantidadeInscricoes = $resultadoQuantidade->fields['quantidade'];
		// echo $quantidade . "<br>";

		// Pego todos os alunos estagiarios menos os que ja entregaram o TC		
		$sqlAlunos = "select alunos.nome, alunos.registro, alunos.id, alunos.telefone, alunos.celular, alunos.email, " .
				" alunos.cpf, alunos.identidade, alunos.nascimento, estagiarios.nivel " .
				" from alunos join estagiarios on alunos.id = estagiarios.id_aluno " .
				" where alunos.registro='$id_aluno' and estagiarios.periodo != '" . PERIODO_ATUAL . "' " .
				" group by estagiarios.id_aluno ";

		$sqlAlunos = "select alunos.id from alunos join estagiarios on alunos.id = estagiarios.id_aluno " .
				" where alunos.registro='$id_aluno' and estagiarios.periodo != '" . PERIODO_ATUAL . "' " .
				" group by estagiarios.id_aluno ";

/*				
		$sqlAlunos = "select alunos.nome, alunos.registro, alunos.id, alunos.telefone, alunos.celular, alunos.email, " .
				" alunos.cpf, alunos.identidade, alunos.nascimento, estagiarios.nivel " .
				" from alunos join estagiarios on alunos.id = estagiarios.id_aluno " .
				" where alunos.registro='$id_aluno' " .
				" group by estagiarios.id_aluno ";
*/
	
		// echo $sqlAlunos . "<br>";

		$resultadoAlunos = $db->Execute($sqlAlunos);
		if($resultadoAlunos === false) die ("N�o foi poss�vel consultar a tabela alunos");
		$quantidade = $resultadoAlunos->RecordCount();

		if ($quantidade == 0) {
				$sqlAlunosNovos = "select nome, registro, id, telefone, celular, email, cpf, identidade, nascimento from alunosNovos where registro='$id_aluno'";

/*
				$sqlAlunosNovos = "select alunosNovos.nome, alunosNovos.registro, alunosNovos.id, alunosNovos.telefone, alunosNovos.celular, alunosNovos.email, " .
						" alunosNovos.cpf, alunosNovos.identidade, alunosNovos.nascimento, estagiarios.nivel " .
						" from alunosNovos join estagiarios on alunosNovos.registro = estagiarios.registro " .
						" where alunosNovos.registro='$id_aluno' and estagiarios.periodo = '" . PERIODO_ATUAL . "' " .
						" group by estagiarios.id_aluno ";

				$sqlAlunosNovos = "select alunosNovos.nome, alunosNovos.registro, alunosNovos.id, alunosNovos.telefone, alunosNovos.celular, alunosNovos.email, " .
						" alunosNovos.cpf, alunosNovos.identidade, alunosNovos.nascimento, estagiarios.nivel " .
						" from alunosNovos join estagiarios on alunosNovos.registro = estagiarios.registro " .
						" where alunosNovos.registro='$id_aluno' " .
						" group by estagiarios.id_aluno ";
*/
				// echo $sqlAlunosNovos . "<br>";
				$resultadoAlunosNovos = $db->Execute($sqlAlunosNovos);
				if($resultadoAlunosNovos === false) die ("N�o foi poss�vel consultar a tabela alunosNovos");
				while(!$resultadoAlunosNovos->EOF) {
						$nome = $resultadoAlunosNovos->fields['nome'];
						$registro = $resultadoAlunosNovos->fields['registro'];
						// echo "Novos " . $registro . " " . $nome . "<br>";
						// $instituicao = $resultadoAlunosNovos->fields['instituicao'];

						$inscritos[$i][$ordem] = $resultadoAlunosNovos->fields[$ordem];
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
/*
						$dataCorrigida = split("-",$data);
						$dataSQL = $dataCorrigida[2] . "-" . $dataCorrigida[1] . "-" . $dataCorrigida[0];
				
						$dataSQL = date("d-m-Y",strtotime($data));				
						$inscritos[$i]['data'] = $dataSQL;
*/	
						$inscritos[$i]['aluno'] = 0;
						$i++;

						$resultadoAlunosNovos->MoveNext();
				}
		} 
		/*
		else {
				while(!$resultadoAlunos->EOF) {
						$nome = $resultadoAlunos->fields['nome'];
						$registro = $resultadoAlunos->fields['registro'];
						// echo "Velhos: " . $registro . " " . $nome . "<br>";
						// $instituicao = $resultadoAlunos->fields['instituicao'];
						$inscritos[$i]['nome'] = $resultadoAlunos->fields['nome'];
						$inscritos[$i]['registro'] = $resultadoAlunos->fields['registro'];
						$inscritos[$i]['id'] = $resultadoAlunos->fields['id'];
						$inscritos[$i]['telefone'] = $resultadoAlunos->fields['telefone'];
						$inscritos[$i]['celular'] = $resultadoAlunos->fields['celular'];
						$inscritos[$i]['email'] = $resultadoAlunos->fields['email'];
						$inscritos[$i]['instituicao'] = $resultadoAlunos->fields['instituicao'];
						$inscritos[$i]['quantidade'] = $quantidadeInscricoes;
						
						$dataCorrigida = split("-",$data);
						$dataSQL = $dataCorrigida[2] . "-" . $dataCorrigida[1] . "-" . $dataCorrigida[0];
						$inscritos[$i]['data'] = $dataSQL;
						
						$inscritos[$i]['aluno'] = 1;
						$i++;				
						$resultadoAlunos->MoveNext();
					}
		}
		*/
		$resultado->MoveNext();
}

if (sizeof($inscritos) != 0) {
	sort($inscritos);
}

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("totalAlunos",$i);
$smarty->assign("alunos",$inscritos);
$smarty->display("lista-alunos_mural.tpl");

?>
