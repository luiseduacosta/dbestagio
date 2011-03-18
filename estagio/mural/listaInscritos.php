<?php

/*
 * Created on 13/06/2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once("../autoriza.inc");
include_once("../setup.php");

$id_instituicao = isset($_REQUEST['id_instituicao']) ? $_REQUEST['id_instituicao'] : NULL;

$sqlInstituicao = "select instituicao from mural_estagio where id = $id_instituicao";
$resultadoInstituicao = $db->Execute($sqlInstituicao);
if($resultadoInstituicao === false) die ("Não foi possível consultar a tabela mural_estagio");
$instituicao = $resultadoInstituicao->fields['instituicao'];

$sql = "SELECT id, id_aluno, data FROM mural_inscricao WHERE id_instituicao='$id_instituicao' and periodo='". PERIODO_ATUAL . "'";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela mural_inscricao");
$i = 0;
while(!$resultado->EOF) {
		$id = $resultado->fields['id'];
		$id_aluno = $resultado->fields['id_aluno'];
		$data = date("d-m-Y",strtotime($resultado->fields['data']));

		$sqlAlunos = "select nome, registro, id, telefone, celular, email from alunos where registro=$id_aluno";
		// echo $sqlAlunos . "<br>";
		$resultadoAlunos = $db->Execute($sqlAlunos);
		if($resultadoAlunos === false) die ("Não foi possível consultar a tabela alunos");
		$quantidade = $resultadoAlunos->RecordCount();
		// echo $quantidade . " ";
		if ($quantidade == 0) {
				$sqlAlunosNovos = "select nome, registro, id, telefone, celular, email from alunosNovos where registro=$id_aluno";
				$resultadoAlunosNovos = $db->Execute($sqlAlunosNovos);
				if($resultadoAlunosNovos === false) die ("Não foi possível consultar a tabela alunosNovos");
				while(!$resultadoAlunosNovos->EOF) {
						$nome = $resultadoAlunosNovos->fields['nome'];
						// echo "Novos " . $nome . "<br>";
						// $instituicao = $resultadoAlunosNovos->fields['instituicao'];
						$inscritos[$i]['nome'] = $resultadoAlunosNovos->fields['nome'];
						$inscritos[$i]['id'] = $id;
						$inscritos[$i]['registro'] = $resultadoAlunosNovos->fields['registro'];
						// $inscritos[$i]['id'] = $resultadoAlunosNovos->fields['id'];
						$inscritos[$i]['telefone'] = $resultadoAlunosNovos->fields['telefone'];
						$inscritos[$i]['celular'] = $resultadoAlunosNovos->fields['celular'];
						$inscritos[$i]['email'] = $resultadoAlunosNovos->fields['email'];
						$inscritos[$i]['instituicao'] = $resultadoAlunosNovos->fields['instituicao'];
						$inscritos[$i]['data'] = $data;
						
						$inscritos[$i]['aluno'] = 0;
						$i++;
						$resultadoAlunosNovos->MoveNext();
				}
		} else {
		while(!$resultadoAlunos->EOF) {
				$nome = $resultadoAlunos->fields['nome'];
				// echo "Velhos: " . $nome . "<br>";
				// $instituicao = $resultadoAlunos->fields['instituicao'];
				$inscritos[$i]['nome'] = $resultadoAlunos->fields['nome'];
				$inscritos[$i]['id'] = $id;
				$inscritos[$i]['registro'] = $resultadoAlunos->fields['registro'];
				// $inscritos[$i]['id'] = $resultadoAlunos->fields['id'];
				$inscritos[$i]['telefone'] = $resultadoAlunos->fields['telefone'];
				$inscritos[$i]['celular'] = $resultadoAlunos->fields['celular'];
				$inscritos[$i]['email'] = $resultadoAlunos->fields['email'];
				$inscritos[$i]['instituicao'] = $resultadoAlunos->fields['instituicao'];
				$inscritos[$i]['data'] = $data;
				
				$inscritos[$i]['aluno'] = 1;
				$i++;				
				$resultadoAlunos->MoveNext();
			}
		}
		$resultado->MoveNext();
}

if (sizeof($inscritos) != 0) {
	sort($inscritos);
}

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("inscritos",$inscritos);
$smarty->display("../../mural/listaInscritos.tpl");

?>
