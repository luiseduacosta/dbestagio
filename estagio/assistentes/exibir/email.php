<?php
/*
 * Created on 29/01/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

// echo "<h1>Aguarde: preparando a lista dos supervisores para o envio de e-mail.</h1>";

include("../../pommo_config.php");

// Apago toda a informacao das tabelas
$sql_subs = "truncate table pommo_subscribers";
$res_subs = $db_pommo->Execute($sql_subs);
if($res_subs === false) die ("Não foi possível limpar a tabela pommo_subscribers");

$sql_subs_data = "truncate table pommo_subscriber_data";
$res_subs_data = $db_pommo->Execute($sql_subs_data);
if($res_subs_data === false) die ("Não foi possível limpar a tabela pommo_subscribers_data");

include("../../setup.php");

// Busco todos os supervisores de todos os periodos para serem inseridos nas tabelas
$sql  = "select estagiarios.id, id_supervisor, id_instituicao, periodo, nome, email, instituicao, areas_estagio.area from estagiarios";
$sql .= " join supervisores on supervisores.id = estagiarios.id_supervisor ";
$sql .= " join estagio on estagio.id = estagiarios.id_instituicao ";
$sql .= " join areas_estagio on areas_estagio.id = estagio.area ";
$sql .= " group by estagiarios.id_supervisor, estagiarios.periodo ";
$sql .= " order by estagiarios.periodo, estagiarios.id_supervisor";
// echo $sql . "<br>";
$resultado = $db->Execute($sql); 
while (!$resultado->EOF) {
	$id_supervisor = $resultado->fields['id_supervisor'];
	$nome = $resultado->fields['nome'];
	$email = $resultado->fields['email'];
	$instituicao = $resultado->fields['instituicao'];
	$area = $resultado->fields['area'];
	$periodo = $resultado->fields['periodo'];

	// Somente aqueles que tem e-mail
	if ($email) {
		
		include("../../pommo_config.php");
		
		$sql_email = "insert into pommo_subscribers (email,status) values ('$email',1)";
		// echo $sql_email . "<br>";
		$res_email = $db_pommo->Execute($sql_email); 
		if($res_email === false) die ("Não foi possível inserir dados na tabela pommo_subscribers");
		$subscriber_id = $db_pommo->Insert_ID();

		$sql_email_nome = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (1,'$nome',$subscriber_id)";
		// echo $sql_email_nome . "<br>";
		$res_email_nome = $db_pommo->Execute($sql_email_nome); 
		
		$sql_email_tipo = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (2,'supervisor',$subscriber_id)";
		// echo $sql_email_tipo . "<br>";
		$res_email_tipo = $db_pommo->Execute($sql_email_tipo); 
		
		$sql_email_inst = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (3,'$instituicao',$subscriber_id)";
		// echo $sql_email_inst . "<br>";
		$res_email_inst = $db_pommo->Execute($sql_email_inst); 
		
		$sql_email_area = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (4,'$periodo',$subscriber_id)";
		// echo $sql_email_area . "<br>";
		$res_email_area = $db_pommo->Execute($sql_email_area); 
		
		$sql_email_periodo = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (5,'$area',$subscriber_id)";
		// echo $sql_email_periodo . "<br>";
		$res_email_periodo = $db_pommo->Execute($sql_email_periodo); 
		
		$sql_email_super_estagio = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (6,'super_estagio',$subscriber_id)";
		// echo $sql_email_super_estagio . "<br>";
		$res_email_super_estagio = $db_pommo->Execute($sql_email_super_estagio); 
		
	}
	
	$resultado->MoveNext();
}

echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=../../../pommo/'>";

?>
