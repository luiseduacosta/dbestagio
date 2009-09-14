<?php
/*
 * Created on 06/03/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once("../../pommo_config.php");

// Apago toda a informacao das tabelas
$sql_subs = "truncate table mail_subscribers";
$res_subs = $db_pommo->Execute($sql_subs);
if($res_subs === false) die ("Não foi possível limpar a tabela pommo_subscribers");

$sql_subs_data = "truncate table mail_subscriber_data";
$res_subs_data = $db_pommo->Execute($sql_subs_data);
if($res_subs_data === false) die ("Não foi possível limpar a tabela pommo_subscribers_data");

$sql_pommo_fields = "truncate table mail_fields";
$res_pommo_fields = $db_pommo->Execute($sql_pommo_fields);
if($res_pommo_fields === false) die ("Não foi possível limpar a tabela pommo_fields");

// Insero os campos
$sql_pommo_campos = "
INSERT INTO `mail_fields` (`field_id`, `field_active`, `field_ordering`, `field_name`, `field_prompt`, `field_normally`, `field_array`, `field_required`, `field_type`) VALUES
(1, 'on', 0, 'nome', 'Nome', '', 'a:0:{}', 'on', 'text'),
(2, 'on', 1, 'departamento', 'Departamento', '', 'a:0:{}', 'on', 'text'),
(3, 'on', 2, 'outros', 'Outros', '', 'a:0:{}', 'off', 'text');
";

$res_pommo_campos = $db_pommo->Execute($sql_pommo_campos);
if($res_pommo_campos === false) die ("Não foi possível inserir na tabela mail_fields");

include("../../pommo_config.php");

$sql  = "select email,nome,departamento,outros from eprofesores order by nome";

// echo $sql ."<br>";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar as tabelas eprofessores");
$i = 0;
while (!$resultado->EOF) {
	$email = $resultado->fields['email'];
	$nome = $resultado->fields['nome'];
	$departamento = $resultado->fields['departamento'];
	$outros = $resultado->fields['outros'];

	// echo "$email . ' ' . $registro . ' '. $nome . ' ' . $nivel . ' ' . $periodo . '<br>'";

	if ($email) {

		include("eprofessores_pommo_config.php");

		$sql_email = "insert into mail_subscribers (email,status) values ('$email',1)";
		// echo $sql_email . "<br>";
		$res_email = $db_pommo->Execute($sql_email);
		if($res_email === false) die ("0 Não foi possível inserir dados na tabela pommo_subscribers");
		$subscriber_id = $db_pommo->Insert_ID();

		$sql_email_nome = "insert into mail_subscriber_data (field_id, value, subscriber_id) values (1,'$nome',$subscriber_id)";
		// echo $sql_email_nome . "<br>";
		$res_email_nome = $db_pommo->Execute($sql_email_nome);
		if($res_email_nome === false) die ("1 Não foi possível inserir dados na tabela pommo_subscribers");

		$sql_email_departamento = "insert into mail_subscriber_data (field_id, value, subscriber_id) values (2,'$departamento',$subscriber_id)";
		// $sql_email_departamento . "<br>";
		$res_email_departamento = $db_pommo->Execute($sql_email_departamento);
		if($res_email_departamento === false) die ("2 Não foi possível inserir dados na tabela pommo_subscribers");

		$sql_email_outros = "insert into mail_subscriber_data (field_id, value, subscriber_id) values (3,'$outros',$subscriber_id)";
		// echo $sql_email_outros . "<br>";
		$res_email_outros = $db_pommo->Execute($sql_email_outros);
		if($res_email_outros === false) die ("3 Não foi possível inserir dados na tabela pommo_subscribers");

	}

	// echo "<br>";

	$i++;
	$resultado->MoveNext();
}

// echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=../../../pommo/'>";
echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=http://web.intranet.ess.ufrj.br/mailing_pommo/'>";

?>
