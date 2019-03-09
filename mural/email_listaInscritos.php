<?php
/*
 * Created on 06/03/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

$id_instituicao = isset($_REQUEST['id_instituicao']) ? $_REQUEST['id_instituicao'] : NULL;

require_once("../pommo_config.php");

// Apago toda a informacao das tabelas
$sql_subs = "truncate table pommo_subscribers";
$res_subs = $db_pommo->Execute($sql_subs);
if ($res_subs === false) die ("Não foi possível limpar a tabela pommo_subscribers");

$sql_subs_data = "truncate table pommo_subscriber_data";
$res_subs_data = $db_pommo->Execute($sql_subs_data);
if ($res_subs_data === false) die ("Não foi possível limpar a tabela pommo_subscribers_data");

$sql_pommo_fields = "truncate table pommo_fields";
$res_pommo_fields = $db_pommo->Execute($sql_pommo_fields);
if ($res_pommo_fields === false) die ("Não foi possível limpar a tabela pommo_fields");

// Insero os campos
$sql_pommo_campos = "
INSERT INTO `pommo_fields` (`field_id`, `field_active`, `field_ordering`, `field_name`, `field_prompt`, `field_normally`, `field_array`, `field_required`, `field_type`) VALUES
(1, 'on', 0, 'Nome', 'nome', '', 'a:0:{}', 'on', 'text'),
(2, 'on', 1, 'Registro', 'registro', '', 'a:0:{}', 'on', 'text')
";
$res_pommo_campos = $db_pommo->Execute($sql_pommo_campos);
if($res_pommo_campos === false) die ("Não foi possível inserir na tabela pommo_fields");

include("../setup.php");

$sql = "SELECT id, id_aluno " .
		" FROM mural_inscricao " .
		" WHERE id_instituicao='$id_instituicao' and periodo='". PERIODO_ATUAL . "'";

// echo $sql ."<br>";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar as tabelas alunos, estagiarios");
$i = 0;
while (!$resultado->EOF) {

	$id_aluno = $resultado->fields['id_aluno'];

	include('../db.inc');	
	// Primeiro busco na tabela dos alunos estagiarios
	$sql_alunos = "select registro, nome, email from alunos where registro=$id_aluno";
	$res_alunos = $db->Execute($sql_alunos);
	$registro = $res_alunos->fields['registro'];
	if ($registro) {
		while (!$res_alunos->EOF) {	
			$email = $res_alunos->fields['email'];
			$registro = $res_alunos->fields['registro'];
			$nome = $res_alunos->fields['nome'];
			// $nivel = $res_alunos->fields['nivel'];
			// $periodo = $res_alunos->fields['periodo'];
			$res_alunos->MoveNext();
		}
	} else {
		// Logo busco na tabela dos aluno novos
		$sql_alunos_novos = "select registro, nome, email from alunosNovos where registro=$id_aluno";
		// echo $sql_alunos_novos . '<br>';
		$res_alunos_novos = $db->Execute($sql_alunos_novos);
		while (!$res_alunos_novos->EOF) {	
			$email = $res_alunos_novos->fields['email'];
			$registro = $res_alunos_novos->fields['registro'];
			$nome = $res_alunos_novos->fields['nome'];
			// $nivel = $resultado->fields['nivel'];
			// $periodo = $resultado->fields['periodo'];
			$res_alunos_novos->MoveNext();
		}
	}
	// echo "$email . ' ' . $registro . ' '. $nome . ' ' . $nivel . ' ' . $periodo . '<br>'";

	if ($email) {
		include("../pommo_config.php");
		$sql_email = "insert into pommo_subscribers (email,status) values ('$email',1)";
		// echo $sql_email . "<br>";
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
	}
	// echo "<br>";
	$i++;
	$resultado->MoveNext();
}

// echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=../../pommo/'>";
echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=http://novell.ess.ufrj.br/pommo/'>";

?>
