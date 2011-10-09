<?php
/*
 * Created on 06/03/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once("../pommo_config.php");
require_once("../pommo_tabelas.php");

// Insero os campos na tabela fields
$sql_pommo_campos = "
INSERT INTO `pommo_fields` (`field_id`, `field_active`, `field_ordering`, `field_name`, `field_prompt`, `field_normally`, `field_array`, `field_required`, `field_type`) VALUES
(1, 'on', 0, 'Nome', 'nome', '', 'a:0:{}', 'on', 'text'),
(2, 'on', 1, 'Registro', 'registro', '', 'a:0:{}', 'on', 'text'),
(3, 'on', 2, 'Instituicao', 'instituicao', '', 'a:0:{}', 'on', 'text'),
(4, 'on', 3, 'Aluno', 'aluno', '', 'a:0:{}', 'on', 'text'),
(5, 'on', 4, 'TC', 'inscrito', '', 'a:0:{}', 'on', 'text')
";
$res_pommo_campos = $db_pommo->Execute($sql_pommo_campos);
if ($res_pommo_campos === false) die ("Não foi possível inserir na tabela pommo_fields");

include("../setup.php");

$sql = "SELECT id_aluno, data FROM mural_inscricao WHERE periodo='". PERIODO_ATUAL . "' group by id_aluno";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela alunos");

while (!$resultado->EOF) {
		$id_aluno = $resultado->fields['id_aluno'];

		include('../db.inc');

		// Primeiro busco entre os alunos estagiarios
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
				if ($resultadoAlunosNovos === false) die ("Não foi possível consultar a tabela alunosNovos");
				while (!$resultadoAlunosNovos->EOF) {
						$email = $resultadoAlunosNovos->fields['email'];
						$nome = $resultadoAlunosNovos->fields['nome'];
						$registro = $resultadoAlunosNovos->fields['registro'];
						// echo "Novo " . $registro . " " . $nome . "<br>";
						
						$aluno = 'novo'; // Aluno novo

						// Entregou o termo de compromiso?
						$sql_estagiario = "select id, tc from estagiarios where registro = '$id_aluno' and periodo ='" . PERIODO_ATUAL . "'";
						// echo $sql_estagiario . "<br>";
						$resultado_estagiario = $db->Execute($sql_estagiario);
						$q_alunos = $resultado_estagiario->RecordCount();
						$tc = $resultado_estagiario->fields['tc'];
						// echo 'TC ' . $tc . "<br>";
						
						$resultadoAlunosNovos->MoveNext();
				}
		} else {
				while (!$resultadoAlunos->EOF) {
						$email = $resultadoAlunos->fields['email'];
						$nome = $resultadoAlunos->fields['nome'];
						$registro = $resultadoAlunos->fields['registro'];
						// echo "<span style='background-color:green'>Aluno estagi�rio:</span> " . $registro . " " . $nome . "<br>";
						
						$aluno = 'estagiario'; // Aluno estagiario

						// Entregou o termo de compromiso?
						$sql_estagiario = "select id, tc from estagiarios where registro = '$id_aluno' and periodo ='" . PERIODO_ATUAL . "'";
						// echo $sql_estagiario . "<br>";
						$resultado_estagiario = $db->Execute($sql_estagiario);
						$q_alunos = $resultado_estagiario->RecordCount();
						$tc = $resultado_estagiario->fields['tc'];
						// echo 'TC ' . $tc . "<br>";
						
						// echo "<br>";
						
						$resultadoAlunos->MoveNext();
					}
		}


		if ($email) {
			include("../pommo_config.php");
			$sql_email = "insert into pommo_subscribers (email,status) values ('$email',1)";
			// echo $n++ . " " . $sql_email . "<br>";
			$res_email = $db_pommo->Execute($sql_email);
			if($res_email === false) die ("0 Não foi possível inserir dados na tabela pommo_subscribers");
			$subscriber_id = $db_pommo->Insert_ID();

			$sql_email_nome = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (1,'$nome',$subscriber_id)";
			// echo $sql_email_nome . "<br>";
			$res_email_nome = $db_pommo->Execute($sql_email_nome);
			if($res_email_nome === false) die ("1 Não foi possível inserir dados na tabela pommo_subscribers");

			$sql_email_registro = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (2,'$registro',$subscriber_id)";
			// echo $sql_email_registro . "<br>";
			$res_email_registro = $db_pommo->Execute($sql_email_registro);
			if($res_email_registro === false) die ("2 Não foi possível inserir dados na tabela pommo_subscribers");

			$sql_email_instituicao = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (3,'$instituicao',$subscriber_id)";
			// echo $sql_email_registro . "<br>";
			$res_email_instituicao = $db_pommo->Execute($sql_email_instituicao);
			if($res_email_instituicao === false) die ("3 Não foi possível inserir dados na tabela pommo_subscribers");

			$sql_email_aluno = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (4,'$aluno',$subscriber_id)";
			// echo $sql_email_aluno . "<br>";
			$res_email_aluno = $db_pommo->Execute($sql_email_aluno);
			if($res_email_aluno === false) die ("4 Não foi possível inserir dados na tabela pommo_subscribers");

			$sql_email_inscrito = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (5,'$tc',$subscriber_id)";
			// echo $sql_email_inscrito . "<br>";
			$res_email_inscrito = $db_pommo->Execute($sql_email_inscrito);
			if($res_email_inscrito === false) die ("5 Não foi possível inserir dados na tabela pommo_subscribers");

		}

		$resultado->MoveNext();
}

// echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=../../pommo/'>";
echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=http://novell.ess.ufrj.br/pommo/'>";

?>
