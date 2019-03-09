<?php
/*
 * Created on 29/01/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

// echo "<h1>Aguarde: preparando a lista dos supervisores para o envio de e-mail.</h1>";

$periodo = isset($_REQUEST['periodo']) ? $_REQUEST['periodo'] : NULL;

if (!$periodo) {
	die("Selecione período");
}

include("../../pommo_config.php");

// Apago toda a informacao das tabelas
$sql_subs = "truncate table pommo_subscribers";
$res_subs = $db_pommo->Execute($sql_subs);
if ($res_subs === false) die ("Não foi possível limpar a tabela pommo_subscribers");

$sql_subs_data = "truncate table pommo_subscriber_data";
$res_subs_data = $db_pommo->Execute($sql_subs_data);
if ($res_subs_data === false) die ("Não foi possível limpar a tabela pommo_subscribers_data");

$sql_pommo_fields = "truncate table pommo_fields";
$res_pommo_fields = $db_pommo->Execute($sql_pommo_fields);
if ($res_pommo_fields === false) die ("Náo foi possível limpar a tabela pommo_fields");

$sql_pommo_campos = "
INSERT INTO `pommo_fields` (`field_id`, `field_active`, `field_ordering`, `field_name`, `field_prompt`, `field_normally`, `field_array`, `field_required`, `field_type`) VALUES
(1, 'on', 0, 'nome', 'Nome', '', 'a:0:{}', 'on', 'text'),
(2, 'on', 1, 'instituicao', 'Instituição', '', 'a:0:{}', 'off', 'text'),
(3, 'on', 2, 'area', 'Area', '', 'a:0:{}', 'off', 'text'),
(4, 'on', 3, 'periodo', 'Período', '', 'a:0:{}', 'off', 'text');
";
$res_pommo_campos = $db_pommo->Execute($sql_pommo_campos);
if ($res_pommo_campos === false) die ("Não foi possível inserir na tabela pommo_fields");

include("../../setup.php");

// Busco todos os supervisores de todos os periodos para serem inseridos nas tabelas
$sql  = "select estagiarios.id, id_supervisor, id_instituicao, periodo, nome, email, instituicao, areas_estagio.area from estagiarios";
$sql .= " left join supervisores on supervisores.id = estagiarios.id_supervisor ";
$sql .= " left join estagio on estagio.id = estagiarios.id_instituicao ";
$sql .= " left join areas_estagio on areas_estagio.id = estagio.area ";
$sql .= " where estagiarios.periodo='$periodo' ";
$sql .= " group by estagiarios.id_supervisor ";
$sql .= " order by estagiarios.id_supervisor";
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
		
		$sql_email_inst = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (2,'$instituicao',$subscriber_id)";
		// echo $sql_email_inst . "<br>";
		$res_email_inst = $db_pommo->Execute($sql_email_inst); 

		$sql_email_periodo = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (3,'$area',$subscriber_id)";
		// echo $sql_email_periodo . "<br>";
		$res_email_periodo = $db_pommo->Execute($sql_email_periodo);

		$sql_email_area = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (4,'$periodo',$subscriber_id)";
		// echo $sql_email_area . "<br>";
		$res_email_area = $db_pommo->Execute($sql_email_area); 

	}
	
	$resultado->MoveNext();
}

// echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=../../../pommo/'>";
echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=http://web.intranet.ess.ufrj.br/pommo/'>";

?>
